#!/bin/bash

echo "Stopping all running containers..."
docker ps -q | xargs -r docker stop

echo "Removing all containers..."
docker ps -aq | xargs -r docker rm

echo "Removing unused volumes..."
docker volume ls -q | xargs -r docker volume rm

echo "Removing unused networks..."
docker network prune -f

echo "Removing unused images..."
docker image prune -a -f

echo "Docker cleanup complete."