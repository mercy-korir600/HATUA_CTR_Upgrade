#!/bin/bash

# Script: cleanup_unsubmitted_protocols.sh

SERVICE="web"

COMMAND="cd /var/www/html/app && Console/cake unsubmitted_protocols_cleanup cleanup"

echo "Starting unsubmitted protocols cleanup..."

# Execute the command inside the docker compose service
docker compose exec "$SERVICE" bash -lc "$COMMAND"

# Check result
if [ $? -eq 0 ]; then
    echo "Cleanup completed successfully."
else
    echo "Cleanup failed."
fi
