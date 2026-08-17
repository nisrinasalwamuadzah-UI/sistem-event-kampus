# Stage 1: Build Frontend Assets
FROM node:22-alpine AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# Stage 2: Serve Application
# serversideup/php:8.4-fpm-nginx is heavily optimized for Laravel in production
FROM serversideup/php:8.4-fpm-nginx

# Set production environment variables
ENV APP_ENV=production \
    APP_DEBUG=false \
    PHP_OPCACHE_ENABLE=1

# Temporarily switch to root to copy files with proper ownership
USER root

# Install gd (for composer requirements) and imagick (for PNG generation)
RUN install-php-extensions gd imagick

# Copy application codebase
COPY --chown=www-data:www-data . /var/www/html/



# Copy built Vite assets from the frontend stage
COPY --chown=www-data:www-data --from=frontend /app/public/build /var/www/html/public/build

# Install PHP dependencies for production (as root to bypass permissions)
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN apt-get update && apt-get install -y git unzip && rm -rf /var/lib/apt/lists/* && \
    composer install --no-dev --optimize-autoloader --no-interaction && \
    composer clear-cache && \
    chown -R www-data:www-data /var/www/html

# Switch back to the unprivileged www-data user
USER www-data

# Create storage symlink
RUN php artisan storage:link

