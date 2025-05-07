#!/bin/bash

# Set the broadcast driver to null in the .env file
echo "Setting BROADCAST_DRIVER=null in .env file"
sed -i "s/^BROADCAST_DRIVER=.*/BROADCAST_DRIVER=null/" .env

# Run the standard Laravel deployment commands
php artisan config:cache
php artisan route:cache
php artisan view:cache
