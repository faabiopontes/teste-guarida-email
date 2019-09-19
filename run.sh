#!/bin/bash

echo Uploading Application container
docker-compose up --build -d

echo Install dependencies
docker exec -it php bash -c "cd /var/www/html && php composer.phar install"

echo Information of new containers
docker ps -a