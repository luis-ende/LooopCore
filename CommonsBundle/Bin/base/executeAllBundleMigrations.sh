#!/bin/bash
cd `dirname $0`/../../../../../

for VENDOR in src/*; do 
    for BUNDLE in $VENDOR/*; do 
        if [ -r $BUNDLE/Bin/executeMigrations.sh ] 
            then
                echo 
                echo "Executing Migrations for $BUNDLE..."
                echo 
                bash $BUNDLE/Bin/executeMigrations.sh
        fi
    done
done