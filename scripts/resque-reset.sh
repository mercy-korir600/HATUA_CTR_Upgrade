#!/usr/bin/env bash

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

QUEUE="${1:-default}"
 
docker compose exec -T web sh -c 'sudo app/Console/cake CakeResque.CakeResque stop -a -f || true'

docker compose exec -T redis sh -c '
  redis-cli DEL resque:workers ResqueWorker PausedWorker >/dev/null 2>&1 || true
  redis-cli --scan --pattern "resque:worker:*" | while read -r key; do
    redis-cli DEL "$key" >/dev/null 2>&1 || true
  done
'

docker compose exec -T web sh -c "sudo app/Console/cake CakeResque.CakeResque start --queue=${QUEUE}"
docker compose exec -T web sh -c 'sudo app/Console/cake CakeResque.CakeResque stats'
