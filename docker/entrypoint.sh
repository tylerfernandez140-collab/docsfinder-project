#!/bin/bash
set -e

# Ensure permissions for Laravel writable directories
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Ensure storage symlink exists (for file uploads & public storage)
if [ ! -L "/var/www/html/public/storage" ]; then
    php artisan storage:link || true
fi

# Clear caches (safe)
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Rebuild optimized caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Discover packages (important if new providers installed)
php artisan package:discover --ansi

# Run database migrations (safe in container startup)
php artisan migrate --force || true

# Adjust Apache to use Render's dynamic port
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/:80/:${PORT}/" /etc/apache2/sites-available/000-default-laravel.conf

# Start Apache
exec apache2-foreground
