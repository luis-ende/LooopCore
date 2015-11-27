#!/bin/bash

DB_PREFIX=$1;
BUNDLE_FILTER=$2;
CONFIG_FILE=$3;

cd `dirname $0`/../../../../../

php app/console orm:generate-super-entities --generate-annotations=0 --regenerate-entities=1 --super-dest-path="src" --super-dest-namespace="Generated" --base-trait="\LooopCore\FrameworkBundle\Entity\EntityTrait" --base-interface="\LooopCore\FrameworkBundle\Entity\EntityInterface" --filter="$BUNDLE_FILTER" src