#!/usr/bin/env bash

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

DB_SERVICE="${DB_SERVICE:-db}"
WEB_SERVICE="${WEB_SERVICE:-web}"
TARGET_GROUP_ID="${TARGET_GROUP_ID:-1}"

usage() {
  cat <<'EOF'
Usage:
  ./scripts/promote-user-to-admin.sh <user-id|username|email> [group-id]

Examples:
  ./scripts/promote-user-to-admin.sh 42
  ./scripts/promote-user-to-admin.sh pi_user@example.com
  ./scripts/promote-user-to-admin.sh principalinvestigator 1

Notes:
  - Default target group is 1 (PPB Admins).
  - This script updates users.group_id, repairs the user's ARO linkage,
    removes direct user-level ACL overrides, and rebuilds the ARO tree.
EOF
}

if [[ $# -lt 1 || $# -gt 2 ]]; then
  usage
  exit 1
fi

IDENTIFIER="$1"
if [[ $# -eq 2 ]]; then
  TARGET_GROUP_ID="$2"
fi

if ! [[ "$TARGET_GROUP_ID" =~ ^[0-9]+$ ]]; then
  echo "Target group id must be numeric." >&2
  exit 1
fi

require_running_service() {
  local service="$1"
  if ! docker compose ps --status running "$service" >/dev/null 2>&1; then
    echo "Service '$service' is not running. Start the stack first with: docker compose up -d $service" >&2
    exit 1
  fi
}

require_running_service "$DB_SERVICE"
require_running_service "$WEB_SERVICE"

mysql_exec() {
  local sql="$1"
  docker compose exec -T "$DB_SERVICE" sh -c 'MYSQL_PWD="$MYSQL_ROOT_PASSWORD" mysql -N -uroot "$MYSQL_DATABASE"' <<< "$sql"
}

sql_escape() {
  local value="$1"
  value="${value//\\/\\\\}"
  value="${value//\'/\'\'}"
  printf '%s' "$value"
}

if [[ "$IDENTIFIER" =~ ^[0-9]+$ ]]; then
  USER_ROW="$(mysql_exec "SELECT id, username, email, group_id, is_active, deactivated FROM users WHERE id = ${IDENTIFIER} LIMIT 1;")"
else
  ESCAPED_IDENTIFIER="$(sql_escape "$IDENTIFIER")"
  USER_ROW="$(mysql_exec "SELECT id, username, email, group_id, is_active, deactivated FROM users WHERE username = '${ESCAPED_IDENTIFIER}' OR email = '${ESCAPED_IDENTIFIER}' LIMIT 1;")"
fi

if [[ -z "$USER_ROW" ]]; then
  echo "User not found for identifier: $IDENTIFIER" >&2
  exit 1
fi

IFS=$'\t' read -r USER_ID USERNAME EMAIL CURRENT_GROUP_ID IS_ACTIVE DEACTIVATED <<< "$USER_ROW"

GROUP_ROW="$(mysql_exec "SELECT id, name FROM groups WHERE id = ${TARGET_GROUP_ID} LIMIT 1;")"
if [[ -z "$GROUP_ROW" ]]; then
  echo "Target group ${TARGET_GROUP_ID} was not found in groups." >&2
  exit 1
fi

IFS=$'\t' read -r GROUP_ID GROUP_NAME <<< "$GROUP_ROW"

GROUP_ARO_ID="$(mysql_exec "SELECT id FROM aros WHERE model = 'Group' AND foreign_key = ${TARGET_GROUP_ID} ORDER BY id LIMIT 1;")"
if [[ -z "$GROUP_ARO_ID" ]]; then
  echo "No Group ARO found for group ${TARGET_GROUP_ID}." >&2
  exit 1
fi

USER_ARO_IDS="$(mysql_exec "SELECT id FROM aros WHERE model = 'User' AND foreign_key = ${USER_ID} ORDER BY id;")"

if [[ -z "$USER_ARO_IDS" ]]; then
  mysql_exec "INSERT INTO aros (parent_id, model, foreign_key, alias, lft, rght) VALUES (${GROUP_ARO_ID}, 'User', ${USER_ID}, NULL, NULL, NULL);"
  USER_ARO_IDS="$(mysql_exec "SELECT id FROM aros WHERE model = 'User' AND foreign_key = ${USER_ID} ORDER BY id;")"
fi

CANONICAL_ARO_ID="$(printf '%s\n' "$USER_ARO_IDS" | head -n 1)"

mysql_exec "
START TRANSACTION;
UPDATE users
SET group_id = ${TARGET_GROUP_ID},
    is_active = 1,
    deactivated = 0
WHERE id = ${USER_ID};

UPDATE aros
SET parent_id = ${GROUP_ARO_ID},
    lft = NULL,
    rght = NULL
WHERE model = 'User'
  AND foreign_key = ${USER_ID};

-- Direct user-level ACL rows can still override inherited admin access.
DELETE aa
FROM aros_acos aa
INNER JOIN aros a ON a.id = aa.aro_id
WHERE a.model = 'User'
  AND a.foreign_key = ${USER_ID};

DELETE FROM aros
WHERE model = 'User'
  AND foreign_key = ${USER_ID}
  AND id <> ${CANONICAL_ARO_ID};

COMMIT;
"

docker compose exec -T "$WEB_SERVICE" sh -c 'app/Console/cake acl_extras.acl_extras recover aro'
docker compose exec -T "$WEB_SERVICE" sh -c 'app/Console/cake acl_extras.acl_extras verify aro'

echo
echo "Promoted user to ${GROUP_NAME}."
echo
mysql_exec "
SELECT id, username, email, group_id, is_active, deactivated
FROM users
WHERE id = ${USER_ID};

SELECT id, parent_id, model, foreign_key, lft, rght
FROM aros
WHERE model = 'User'
  AND foreign_key = ${USER_ID}
ORDER BY id;
"

echo
echo "Have the user log out and log back in before testing admin routes."
