#!/bin/sh

# Run migrations
php artisan migrate --force

# Caching
php artisan optimize

# Execute the provided command (which is typically php-fpm)
exec "$@"
