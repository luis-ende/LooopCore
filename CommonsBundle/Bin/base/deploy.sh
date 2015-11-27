#!/bin/bash
#
# Deploy assets and execute migrations

cd `dirname $0`/../../../../../

echo "Creating all Auto-Generated Entity traits"
## run create Entities on all src/ bundles
for BUNDLE_MAIN_PATH in `ls src`
    do
    for BUNDLE_SUB_PATH in `ls src/$BUNDLE_MAIN_PATH` 
        do
        CREATE_ENTITITES_COMMAND="src/$BUNDLE_MAIN_PATH/$BUNDLE_SUB_PATH/Bin/createEntities.sh"
        if [ -e $CREATE_ENTITITES_COMMAND ] 
            then
            /bin/bash $CREATE_ENTITITES_COMMAND
        fi
    done
done

echo "Installing assets"
php app/console assets:install --symlink
php app/console assetic:dump
php app/console assetic:dump --env=prod


echo "Execute all migrations"
bash src/LooopCore/CommonsBundle/Bin/base/executeAllBundleMigrations.sh
