#!/bin/bash
set -e

echo "Starting Laravel application setup..."

echo "------------------------------------------------"
echo "DEBUG: Database Environment Variables"
echo "------------------------------------------------"
echo "DB_CONNECTION: $DB_CONNECTION"
echo "DB_HOST: $DB_HOST"
echo "DB_PORT: $DB_PORT"
echo "DB_DATABASE: $DB_DATABASE"
echo "DB_USERNAME: $DB_USERNAME"
echo "------------------------------------------------"

# Wait for database to be ready using mysqladmin ping
echo "Waiting for database to be ready..."
max_attempts=30
attempt=0

until mysqladmin ping -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USERNAME" -p"$DB_PASSWORD" --silent 2>/dev/null; do
    attempt=$((attempt + 1))
    if [ $attempt -ge $max_attempts ]; then
        echo "ERROR: Database did not become ready in time"
        echo "Please check:"
        echo "  1. Database container/service is running"
        echo "  2. DB_HOST, DB_PORT, DB_USERNAME, DB_PASSWORD are correct"
        echo "  3. Database firewall allows connections"
        exit 1
    fi
    echo "Database is unavailable - sleeping (attempt $attempt/$max_attempts)"
    sleep 2
done

echo "Database is ready!"

# Set proper permissions for Laravel directories
echo "Setting permissions for storage and cache directories..."
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/storage /var/www/bootstrap/cache 2>/dev/null || true

# Create storage link if it doesn't exist
if [ ! -L /var/www/public/storage ]; then
    echo "Creating storage symlink..."
    php artisan storage:link 2>/dev/null || echo "Warning: Could not create storage link (may already exist)"
fi

# Run migrations
echo "Running database migrations..."
php artisan migrate --force || {
    echo "ERROR: Migration failed!"
    echo "Check database connection and permissions"
    exit 1
}

# Clear and cache configuration for production
echo "Optimizing Laravel configuration..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Laravel setup complete. Starting PHP-FPM..."

# Execute the CMD (php-fpm)
exec "$@"
