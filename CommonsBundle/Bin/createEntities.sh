#!/bin/bash

. `dirname $0`/_bundleConfig
bash `dirname $0`/../../../LooopCore/CommonsBundle/Bin/base/createEntities.sh "$DB_PREFIX" "$BUNDLE_FILTER" "$CONFIG_FILE"