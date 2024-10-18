#!/bin/sh

# To ensure the current Docker container environment is passed into the cron sub-processes
env >> /etc/environment
#
set -e
#
ROLE=${CONTAINER_ROLE:-api}
env=${APP_ENV:-production}
#
echo "Starting ...... $ROLE/$env"
#
#
#
if [ "$ROLE" = "api" ]; then
    exec php-fpm
else
    echo "Could not match the container ROLE \"$ROLE\""
    exit 1
fi
