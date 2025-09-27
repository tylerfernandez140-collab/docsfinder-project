#!/bin/bash
set -e

# Ensure permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Clear and cache Laravel configs/routes/views
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache

# Only run migrations if needed
# php artisan migrate --force

# Adjust Apache to use Render dynamic port
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf

# Start Apache
exec apache2-foreground
