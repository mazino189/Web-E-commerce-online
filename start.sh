#!/bin/sh

# Run migrations using the production database
php artisan migrate --force

# Cache configuration, routes, and views for better performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start the Apache server
exec apache2-foreground
