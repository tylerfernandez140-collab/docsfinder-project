# -------------------------------
# Stage 1: Build Frontend (Vite)
# -------------------------------
FROM node:18 AS frontend

WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# -------------------------------
# Stage 2: Backend (Laravel + PHP + Apache)
# -------------------------------
FROM php:8.2-apache AS backend

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    git curl unzip libpq-dev libonig-dev libzip-dev postgresql-client \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring zip \
    && docker-php-ext-enable pdo_pgsql \
    && php -m \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy backend code and frontend build
COPY . .
COPY --from=frontend /app/public/build ./public/build

# Apache config
COPY docker/000-default-laravel.conf /etc/apache2/sites-available/000-default-laravel.conf
RUN rm -f /etc/apache2/sites-enabled/000-default.conf
RUN a2ensite 000-default-laravel.conf
RUN a2enmod rewrite

# Git safe directory
RUN git config --global --add safe.directory /var/www/html

# Install PHP dependencies
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV APP_URL=http://localhost
RUN composer install --no-dev --optimize-autoloader \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache \
    && php artisan migrate --force

# Set entrypoint
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]

# Expose (Render ignores this, but nice to have)
EXPOSE 80
