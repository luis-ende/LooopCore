#!/bin/bash
cd `dirname $0`/../../../../../

CONFIG_FILE=$1

php app/console doctrine:migrations:status --configuration $CONFIG_FILE $2 $3 $4 $5
