#!/bin/bash
#
# Deploy everything, including download via composer and patches
# deploy.sh is included (assets and migrations)

cd `dirname $0`/../../../../../


echo "Fix cache/log dir permissions"
rm -rf app/cache/*
rm -rf app/logs/*
HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
sudo setfacl -Rn -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs
sudo setfacl -dRn -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs
sudo chmod -R g+w app/cache app/logs


echo "Update installed vendor software"

# Update Composer if it exists, otherwise do new download
if [ -a composer.phar ]; then
	# Composer Self-Update
	php composer.phar self-update
else
	# Download Composer
	curl -sS https://getcomposer.org/installer | php
fi

# Composer update by checking composer.json
php composer.phar update --prefer-dist

# Do "ordinary" deployment
bash deploy.sh
