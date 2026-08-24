#!/bin/bash
set -e

# cron forks jobs with a bare-bones environment of its own - it does NOT
# inherit the environment docker-compose.yml gave this container (DB_HOST,
# DB_PASSWORD, REDIS_*, etc.), even though supervisord (which starts cron)
# does. Without this, `cake review_deadline_alert` run from cron would fall
# back to database.php's hardcoded defaults instead of whatever this
# specific container was actually configured with.
#
# Dump the container's current DB_*/REDIS_* variables to a file the
# crontab entries in docker/crontab source before running any cake
# console command, so a scheduled run sees the same config a normal web
# request does.
env | grep -E '^(DB_|REDIS_)[A-Z_]*=' > /etc/container.env
chmod 644 /etc/container.env

exec "$@"
