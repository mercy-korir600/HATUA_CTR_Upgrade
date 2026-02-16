#!/bin/bash

# Name or ID of your Docker container
CONTAINER_NAME="ctr-web-1"

# Command to execute inside the container
COMMAND="app/Console/cake AclExtras.AclExtras aco_sync"

echo "Entering container: $CONTAINER_NAME"
echo "Running command: $COMMAND"

docker exec -it "$CONTAINER_NAME" bash -c "$COMMAND"

# Check exit status
if [ $? -eq 0 ]; then
    echo "ACO sync completed successfully."
else
    echo "ACO sync failed."
fi
