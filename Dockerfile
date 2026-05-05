# Stage 1: Build Assets
FROM node:20-alpine AS assets-builder
WORKDIR /build
COPY app/package*.json ./
RUN npm install
COPY app/ ./
RUN npm run build

# Stage 2: PHP & Runtime
FROM php:8.4-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    postgresql-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    supervisor

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql zip bcmath

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application code
COPY app/ .

# Copy built assets from Stage 1
COPY --from=assets-builder /build/public/build ./public/build

# Install production dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Optimizations
RUN php artisan config:cache && php artisan route:cache

# Copy Nginx configuration
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# Copy Supervisor configuration
COPY docker/supervisord.conf /etc/supervisord.conf

# Copy entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expose port
EXPOSE 10000

# Run entrypoint
ENTRYPOINT ["entrypoint.sh"]
