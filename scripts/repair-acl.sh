#!/usr/bin/env bash

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

DB_SERVICE="${DB_SERVICE:-db}"
WEB_SERVICE="${WEB_SERVICE:-web}"
APP_URL="${APP_URL:-http://127.0.0.1}"

require_running_service() {
  local service="$1"
  if ! docker compose ps --status running "$service" >/dev/null 2>&1; then
    echo "Service '$service' is not running. Start it first with: docker compose up -d $service" >&2
    exit 1
  fi
}

mysql_exec() {
  local sql="$1"
  docker compose exec -T "$DB_SERVICE" sh -c 'MYSQL_PWD="$MYSQL_ROOT_PASSWORD" mysql -N -uroot "$MYSQL_DATABASE"' <<< "$sql"
}

web_exec() {
  local command="$1"
  docker compose exec -T "$WEB_SERVICE" sh -c "$command"
}

require_running_service "$DB_SERVICE"
require_running_service "$WEB_SERVICE"

echo "Repairing ARO records from groups/users..."

mysql_exec "
START TRANSACTION;

INSERT INTO aros (parent_id, model, foreign_key, alias, lft, rght)
SELECT NULL, 'Group', g.id, NULL, NULL, NULL
FROM groups g
LEFT JOIN aros a
  ON a.model = 'Group'
 AND a.foreign_key = g.id
WHERE a.id IS NULL;

DELETE dup
FROM aros dup
INNER JOIN aros keep
  ON keep.model = dup.model
 AND keep.foreign_key = dup.foreign_key
 AND keep.id < dup.id
WHERE dup.model = 'Group';

INSERT INTO aros (parent_id, model, foreign_key, alias, lft, rght)
SELECT ga.id, 'User', u.id, NULL, NULL, NULL
FROM users u
INNER JOIN aros ga
  ON ga.model = 'Group'
 AND ga.foreign_key = u.group_id
LEFT JOIN aros ua
  ON ua.model = 'User'
 AND ua.foreign_key = u.id
WHERE ua.id IS NULL;

UPDATE aros ua
INNER JOIN users u
  ON ua.model = 'User'
 AND ua.foreign_key = u.id
INNER JOIN aros ga
  ON ga.model = 'Group'
 AND ga.foreign_key = u.group_id
SET ua.parent_id = ga.id,
    ua.lft = NULL,
    ua.rght = NULL
WHERE ua.model = 'User';

DELETE aa
FROM aros_acos aa
INNER JOIN aros dup
  ON dup.id = aa.aro_id
INNER JOIN aros keep
  ON keep.model = dup.model
 AND keep.foreign_key = dup.foreign_key
 AND keep.id < dup.id
WHERE dup.model IN ('Group', 'User');

DELETE dup
FROM aros dup
INNER JOIN aros keep
  ON keep.model = dup.model
 AND keep.foreign_key = dup.foreign_key
 AND keep.id < dup.id
WHERE dup.model = 'User';

COMMIT;
"

echo "Recovering ARO tree..."
web_exec 'app/Console/cake acl_extras.acl_extras recover aro'
web_exec 'app/Console/cake acl_extras.acl_extras verify aro'

echo "Syncing ACO tree from controllers..."
web_exec 'app/Console/cake acl_extras.acl_extras aco_sync'
web_exec 'app/Console/cake acl_extras.acl_extras verify aco'

echo "Cleaning orphan permission rows..."
mysql_exec "
DELETE aa
FROM aros_acos aa
LEFT JOIN aros ar
  ON ar.id = aa.aro_id
LEFT JOIN acos ac
  ON ac.id = aa.aco_id
WHERE ar.id IS NULL
   OR ac.id IS NULL;
"

echo "Applying group permissions via /users/initDB ..."
INITDB_OUTPUT="$(web_exec "curl -fsS ${APP_URL}/users/initDB")"

if [[ "$INITDB_OUTPUT" != *"all done"* ]]; then
  echo "initDB did not finish as expected. Output was:" >&2
  printf '%s\n' "$INITDB_OUTPUT" >&2
  exit 1
fi

echo
echo "ACL repair complete."
echo
mysql_exec "
SELECT 'groups', COUNT(*) FROM groups;
SELECT 'users', COUNT(*) FROM users;
SELECT 'group_aros', COUNT(*) FROM aros WHERE model = 'Group';
SELECT 'user_aros', COUNT(*) FROM aros WHERE model = 'User';
SELECT 'acos', COUNT(*) FROM acos;
SELECT 'aros_acos', COUNT(*) FROM aros_acos;
SELECT id, parent_id, model, foreign_key, lft, rght
FROM aros
WHERE model = 'Group'
ORDER BY foreign_key;
"

echo
echo "Signup should work again now."
echo "If you still have an active browser session, log out and test again."
