#!/usr/bin/env bash

set -e

user=""

if [[ "${1:-}" == "-u" ]]; then
  user="${2:-}"
  shift 2
fi

if [[ $# -eq 0 ]]; then
  exit 0
fi

if [[ -z "$user" ]]; then
  exec "$@"
fi

printf -v cmd '%q ' "$@"
exec su -s /bin/bash "$user" -c "$cmd"
