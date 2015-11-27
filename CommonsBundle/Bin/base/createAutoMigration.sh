#!/bin/bash
# base script -> must be executed with 3 parameters

DB_PREFIX=$1;
BUNDLE_FILTER=$2;
CONFIG_FILE=$3;

bash `dirname $0`/createEntities.sh "$DB_PREFIX" "$BUNDLE_FILTER" "$CONFIG_FILE"

cd `dirname $0`/../../../../../

php app/console doctrine:migrations:diff --configuration "$CONFIG_FILE" --filter-expression="/^${DB_PREFIX}_.*/"
