#!/usr/bin/env bash

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

DB_SERVICE="${DB_SERVICE:-db}"
WEB_SERVICE="${WEB_SERVICE:-web}"
REDIS_SERVICE="${REDIS_SERVICE:-redis}"
PHPMYADMIN_SERVICE="${PHPMYADMIN_SERVICE:-phpmyadmin}"

BOOTSTRAP_USERNAME="${BOOTSTRAP_USERNAME:-bootstrap_admin}"
BOOTSTRAP_EMAIL="${BOOTSTRAP_EMAIL:-bootstrap_admin@ctr.local}"
BOOTSTRAP_PASSWORD="${BOOTSTRAP_PASSWORD:-ChangeMe123!}"
BOOTSTRAP_NAME="${BOOTSTRAP_NAME:-CTR Bootstrap Admin}"
BOOTSTRAP_PHONE="${BOOTSTRAP_PHONE:-0700000000}"
BOOTSTRAP_INSTITUTION="${BOOTSTRAP_INSTITUTION:-CTR Bootstrap Setup}"
BOOTSTRAP_QUALIFICATION="${BOOTSTRAP_QUALIFICATION:-System Administrator}"
START_RESQUE="${START_RESQUE:-1}"

usage() {
  cat <<'EOF'
Usage:
  ./scripts/bootstrap-admin-user.sh [username] [email] [password] [full-name]

Examples:
  ./scripts/bootstrap-admin-user.sh
  ./scripts/bootstrap-admin-user.sh admin admin@example.com ChangeMe123! "System Admin"

Environment overrides:
  BOOTSTRAP_USERNAME
  BOOTSTRAP_EMAIL
  BOOTSTRAP_PASSWORD
  BOOTSTRAP_NAME
  BOOTSTRAP_PHONE
  BOOTSTRAP_INSTITUTION
  BOOTSTRAP_QUALIFICATION
  START_RESQUE=0

What this script does:
  1. Starts db, redis, web, and phpmyadmin
  2. Repairs ACL tables
  3. Creates or resets a bootstrap user as applicant/group 5
  4. Promotes that user to admin/group 1
  5. Starts the CakeResque worker
EOF
}

if [[ $# -gt 4 ]]; then
  usage
  exit 1
fi

if [[ $# -ge 1 ]]; then
  BOOTSTRAP_USERNAME="$1"
fi
if [[ $# -ge 2 ]]; then
  BOOTSTRAP_EMAIL="$2"
fi
if [[ $# -ge 3 ]]; then
  BOOTSTRAP_PASSWORD="$3"
fi
if [[ $# -ge 4 ]]; then
  BOOTSTRAP_NAME="$4"
fi

mysql_exec() {
  local sql="$1"
  docker compose exec -T "$DB_SERVICE" sh -c 'MYSQL_PWD="$MYSQL_ROOT_PASSWORD" mysql --protocol=TCP -h127.0.0.1 -P3306 -N -uroot "$MYSQL_DATABASE"' <<< "$sql"
}

sql_escape() {
  local value="$1"
  value="${value//\\/\\\\}"
  value="${value//\'/\'\'}"
  printf '%s' "$value"
}

wait_for_db() {
  local attempt
  for attempt in $(seq 1 60); do
    # MySQL 5.7 starts a temporary init server on first boot; only proceed once
    # the final TCP listener is accepting authenticated queries on port 3306.
    if docker compose exec -T "$DB_SERVICE" sh -c 'MYSQL_PWD="$MYSQL_ROOT_PASSWORD" mysql --protocol=TCP -h127.0.0.1 -P3306 --connect-timeout=2 -N -uroot "$MYSQL_DATABASE" -e "SELECT 1" >/dev/null 2>&1'; then
      return 0
    fi
    sleep 2
  done

  echo "Database service did not become ready in time." >&2
  exit 1
}

wait_for_web() {
  local attempt
  for attempt in $(seq 1 60); do
    if docker compose exec -T "$WEB_SERVICE" php -r '$ctx = stream_context_create(array("http" => array("ignore_errors" => true, "timeout" => 2))); $body = @file_get_contents("http://127.0.0.1/users/login", false, $ctx); exit($body === false ? 1 : 0);' >/dev/null 2>&1; then
      return 0
    fi
    sleep 2
  done

  echo "Web service did not become ready in time." >&2
  exit 1
}

cake_password_hash() {
  local password="$1"
  docker compose exec -T -e APP_PASSWORD="$password" "$WEB_SERVICE" php <<'PHP'
<?php
$password = getenv('APP_PASSWORD');
$core = file_get_contents('/var/www/html/app/Config/core.php');
if (!preg_match("/Configure::write\('Security\.salt', '([^']+)'\);/", $core, $matches)) {
    fwrite(STDERR, "Unable to read Security.salt from app/Config/core.php\n");
    exit(1);
}
echo sha1($matches[1] . $password);
PHP
}

echo "Starting application services..."
docker compose up -d "$DB_SERVICE" "$REDIS_SERVICE" "$WEB_SERVICE" "$PHPMYADMIN_SERVICE"

echo "Waiting for database..."
wait_for_db

echo "Waiting for web..."
wait_for_web

echo "Checking core data..."
GROUP_COUNT="$(mysql_exec 'SELECT COUNT(*) FROM groups;')"
if [[ "$GROUP_COUNT" -eq 0 ]]; then
  echo "No groups found in the database. Import the application data first." >&2
  exit 1
fi

echo "Repairing ACL tables..."
./scripts/repair-acl.sh

COUNTRY_ID="$(mysql_exec "SELECT id FROM countries WHERE LOWER(name) = 'kenya' ORDER BY id LIMIT 1;")"
if [[ -z "$COUNTRY_ID" ]]; then
  COUNTRY_ID="$(mysql_exec 'SELECT id FROM countries ORDER BY id LIMIT 1;')"
fi
COUNTRY_SQL="${COUNTRY_ID:-NULL}"

COUNTY_ID="$(mysql_exec "SELECT id FROM counties WHERE LOWER(county_name) = 'nairobi' ORDER BY id LIMIT 1;")"
if [[ -z "$COUNTY_ID" ]]; then
  COUNTY_ID="$(mysql_exec 'SELECT id FROM counties ORDER BY id LIMIT 1;')"
fi
COUNTY_SQL="${COUNTY_ID:-NULL}"

ESCAPED_USERNAME="$(sql_escape "$BOOTSTRAP_USERNAME")"
ESCAPED_EMAIL="$(sql_escape "$BOOTSTRAP_EMAIL")"
ESCAPED_NAME="$(sql_escape "$BOOTSTRAP_NAME")"
ESCAPED_PHONE="$(sql_escape "$BOOTSTRAP_PHONE")"
ESCAPED_INSTITUTION="$(sql_escape "$BOOTSTRAP_INSTITUTION")"
ESCAPED_QUALIFICATION="$(sql_escape "$BOOTSTRAP_QUALIFICATION")"

USERNAME_ID="$(mysql_exec "SELECT id FROM users WHERE username = '${ESCAPED_USERNAME}' LIMIT 1;")"
EMAIL_ID="$(mysql_exec "SELECT id FROM users WHERE email = '${ESCAPED_EMAIL}' LIMIT 1;")"

if [[ -n "$USERNAME_ID" && -n "$EMAIL_ID" && "$USERNAME_ID" != "$EMAIL_ID" ]]; then
  echo "Username and email belong to different users. Choose a different bootstrap username/email pair." >&2
  exit 1
fi

USER_ID="${USERNAME_ID:-$EMAIL_ID}"
PASSWORD_HASH="$(cake_password_hash "$BOOTSTRAP_PASSWORD")"

echo "Creating or resetting bootstrap user..."
if [[ -n "$USER_ID" ]]; then
  mysql_exec "
  UPDATE users
  SET username = '${ESCAPED_USERNAME}',
      email = '${ESCAPED_EMAIL}',
      password = '${PASSWORD_HASH}',
      confirm_password = '${PASSWORD_HASH}',
      name = '${ESCAPED_NAME}',
      sponsor_email = '${ESCAPED_EMAIL}',
      qualification = '${ESCAPED_QUALIFICATION}',
      phone_no = '${ESCAPED_PHONE}',
      name_of_institution = '${ESCAPED_INSTITUTION}',
      institution_physical = '${ESCAPED_INSTITUTION}',
      institution_address = '${ESCAPED_INSTITUTION}',
      institution_contact = '${ESCAPED_PHONE}',
      county_id = ${COUNTY_SQL},
      country_id = ${COUNTRY_SQL},
      group_id = 5,
      activation_key = '',
      forgot_password = 0,
      is_active = 1,
      deactivated = 0,
      modified = NOW()
  WHERE id = ${USER_ID};
  "
else
  mysql_exec "
  INSERT INTO users (
      username,
      password,
      confirm_password,
      name,
      email,
      sponsor_email,
      qualification,
      phone_no,
      name_of_institution,
      institution_physical,
      institution_address,
      institution_contact,
      county_id,
      country_id,
      group_id,
      activation_key,
      forgot_password,
      is_active,
      deactivated,
      created,
      modified
  ) VALUES (
      '${ESCAPED_USERNAME}',
      '${PASSWORD_HASH}',
      '${PASSWORD_HASH}',
      '${ESCAPED_NAME}',
      '${ESCAPED_EMAIL}',
      '${ESCAPED_EMAIL}',
      '${ESCAPED_QUALIFICATION}',
      '${ESCAPED_PHONE}',
      '${ESCAPED_INSTITUTION}',
      '${ESCAPED_INSTITUTION}',
      '${ESCAPED_INSTITUTION}',
      '${ESCAPED_PHONE}',
      ${COUNTY_SQL},
      ${COUNTRY_SQL},
      5,
      '',
      0,
      1,
      0,
      NOW(),
      NOW()
  );
  "
  USER_ID="$(mysql_exec "SELECT id FROM users WHERE username = '${ESCAPED_USERNAME}' LIMIT 1;")"
fi

echo "Promoting bootstrap user to admin..."
./scripts/promote-user-to-admin.sh "$USER_ID" 1

if [[ "$START_RESQUE" != "0" ]]; then
  echo "Starting CakeResque worker..."
  ./scripts/resque-reset.sh default
else
  echo "Skipping CakeResque startup because START_RESQUE=${START_RESQUE}."
fi

echo
echo "Bootstrap complete."
echo "Login URL: http://localhost:9180/users/login"
echo "phpMyAdmin: http://localhost:8081"
echo "Username: ${BOOTSTRAP_USERNAME}"
echo "Email: ${BOOTSTRAP_EMAIL}"
echo "Password: ${BOOTSTRAP_PASSWORD}"
echo "User ID: ${USER_ID}"
echo
echo "Change the bootstrap password after first login."
