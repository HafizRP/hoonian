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

# Wait for database to be ready
echo "Waiting for database to be ready..."
until php artisan db:show 2>/dev/null; do
    echo "Database is unavailable - sleeping"
    sleep 2
done
echo "Database is ready!"

# Set proper permissions for Laravel directories
echo "Setting permissions for storage and cache directories..."
chmod -R 775 /var/www/storage /var/www/bootstrap/cache
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Create storage link if it doesn't exist
if [ ! -L /var/www/public/storage ]; then
    echo "Creating storage symlink..."
    php artisan storage:link
fi

# Run migrations
echo "Running database migrations..."
php artisan migrate --force

# Clear and cache configuration for production
echo "Optimizing Laravel configuration..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Laravel setup complete. Starting PHP-FPM..."

# Execute the CMD (php-fpm)
exec "$@"
