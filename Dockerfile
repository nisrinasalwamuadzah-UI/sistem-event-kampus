# Stage 1: Build Frontend Assets
FROM node:20-alpine AS frontend
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

# Install GD extension for simple-qrcode
RUN install-php-extensions gd

# Copy application codebase
COPY --chown=www-data:www-data . /var/www/html/



# Copy built Vite assets from the frontend stage
COPY --chown=www-data:www-data --from=frontend /app/public/build /var/www/html/public/build

# Switch back to the unprivileged www-data user
USER www-data

# Install PHP dependencies for production
RUN composer install --no-dev --optimize-autoloader --no-interaction && \
    composer clear-cache

# Create storage symlink
RUN php artisan storage:link

