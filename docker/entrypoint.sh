#!/bin/bash
set -e

# Set proper permissions for Laravel
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Clear and cache Laravel configs/routes/views
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache

# Optional: run migrations on deploy
# php artisan migrate --force

# Start Apache
exec apache2-foreground
