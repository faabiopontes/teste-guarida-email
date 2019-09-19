#!/bin/bash

echo Uploading Application container
docker-compose up --build -d

echo Install dependencies
docker exec -it php bash -c "cd /var/www/html && php composer.phar install"

echo Done! Access it on http://localhost:9001