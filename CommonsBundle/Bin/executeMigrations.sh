#!/bin/bash

. `dirname $0`/_bundleConfig
bash `dirname $0`/../../../LooopCore/CommonsBundle/Bin/base/executeMigrations.sh "$CONFIG_FILE" $@