#!/bin/bash

USER = mohammed

echo $'\e[1;32m'"Downloading files..."$'\e[0m'
echo ""
## DOWNLOADING AND CREATING DOCKER COMPOSE FILES ##
mkdir /home/$USER/docker-db
wget https://raw.githubusercontent.com/HusseinM5/tech-journal/main/SYS265/DockerProject/docker-compose-db.yml -o /home/$USER/docker-db/docker-compose.yml
wget https://raw.githubusercontent.com/HusseinM5/tech-journal/main/SYS265/DockerProject/Dockerfile -o /home/$USER/docker-db/Dockerfile
mkdir /home/$USER/docker-web
wget https://raw.githubusercontent.com/HusseinM5/tech-journal/main/SYS265/DockerProject/docker-compose-web.yml -o /home/$USER/docker-web/docker-compose.yml
wget https://raw.githubusercontent.com/HusseinM5/tech-journal/main/SYS265/DockerProject/index.php -o /home/$USER/docker-web/index.php
wget https://raw.githubusercontent.com/HusseinM5/tech-journal/main/SYS265/DockerProject/server.php -o /home/$USER/docker-web/server.php
wget https://raw.githubusercontent.com/HusseinM5/tech-journal/main/SYS265/DockerProject/errors.php -o /home/$USER/docker-web/errors.php

echo ""
echo $'\e[1;32m'"Starting docker!"$'\e[0m'
echo ""
cd /home/$USER/docker-db
docker-compose up -d
sleep 7
cd /home/$USER/docker-web
docker-compose up -d
sleep 7

echo $'\e[1;32m'"Adding firewall rules!"$'\e[0m'
echo ""
ufw allow 8000
ufw allow 8080
ufw allow 80
ufw allow 443
ufw allow 3306
ufw reload

