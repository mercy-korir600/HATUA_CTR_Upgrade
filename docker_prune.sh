#!/bin/bash

echo "Stopping all running containers..."
docker stop $(docker ps -aq) 2>/dev/null

echo "Removing all containers..."
docker rm $(docker ps -aq) 2>/dev/null

echo "Removing all images..."
docker rmi -f $(docker images -aq) 2>/dev/null

echo "Removing all volumes..."
docker volume rm $(docker volume ls -q) 2>/dev/null

echo "Removing all networks (except default ones)..."
docker network rm $(docker network ls -q) 2>/dev/null

echo "Final system prune..."
docker system prune -a --volumes -f

echo "Docker fully cleaned."