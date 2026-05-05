#!/bin/sh

# Safety check for APP_KEY
if [ -z "$APP_KEY" ]; then
    echo "ERROR: APP_KEY is not set. Laravel will not start."
    exit 1
fi

# Ensure storage and cache are writable
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Database Setup
echo "Running migrations..."
php artisan migrate --force

echo "Seeding database..."
php artisan db:seed --force

# Start Supervisor (which starts PHP-FPM and Nginx)
echo "Starting services..."
exec supervisord -c /etc/supervisord.conf
