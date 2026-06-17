#!/bin/sh

# 1. Cache configurations for performance
echo "Caching Laravel configurations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 2. Run migrations safely
echo "Running database migrations..."
php artisan migrate --force

# 3. Run database seeders safely (resilient fallback is active!)
echo "Running database seeders..."
php artisan db:seed --force

# 4. Start the Apache web server
echo "Starting Apache Web Server..."
exec apache2-foreground
