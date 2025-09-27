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
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql pgsql mbstring zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy Laravel backend code
COPY . .

# Copy frontend build from Stage 1
COPY --from=frontend /app/public/build ./public/build

# Copy Apache config
COPY docker/000-default-laravel.conf /etc/apache2/sites-available/000-default.conf

# Enable site & mod_rewrite
RUN rm -f /etc/apache2/sites-enabled/000-default.conf
RUN a2ensite 000-default.conf
RUN a2enmod rewrite

# Add git safe directory
RUN git config --global --add safe.directory /var/www/html

# Set entrypoint
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set Laravel storage/cache permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 80
EXPOSE 80
