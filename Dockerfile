FROM php:8.2-fpm

# Set environment variables for build
ENV DEBIAN_FRONTEND=noninteractive \
    COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_NO_INTERACTION=1

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    fontconfig \
    fonts-dejavu-core \
    default-mysql-client \
    supervisor \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions (including GD with FreeType for PDF generation)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        opcache

# Install Redis extension
RUN pecl install redis \
    && docker-php-ext-enable redis

# Configure PHP for production
RUN { \
        echo 'opcache.enable=1'; \
        echo 'opcache.memory_consumption=256'; \
        echo 'opcache.interned_strings_buffer=16'; \
        echo 'opcache.max_accelerated_files=10000'; \
        echo 'opcache.revalidate_freq=2'; \
        echo 'opcache.fast_shutdown=1'; \
    } > /usr/local/etc/php/conf.d/opcache.ini

# Configure PHP-FPM
RUN { \
        echo '[www]'; \
        echo 'pm = dynamic'; \
        echo 'pm.max_children = 50'; \
        echo 'pm.start_servers = 10'; \
        echo 'pm.min_spare_servers = 5'; \
        echo 'pm.max_spare_servers = 20'; \
        echo 'pm.max_requests = 500'; \
    } > /usr/local/etc/php-fpm.d/zz-docker.conf

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy composer files first for better layer caching
COPY composer.json composer.lock ./

# Install Composer dependencies (production only)
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --no-progress \
    --prefer-dist \
    --optimize-autoloader

# Copy application files
COPY . /var/www

# Run composer scripts after copying all files
RUN composer dump-autoload --optimize

# Create necessary directories
RUN mkdir -p \
    /var/www/storage/logs \
    /var/www/storage/framework/sessions \
    /var/www/storage/framework/views \
    /var/www/storage/framework/cache/data \
    /var/www/storage/app/public \
    /var/www/bootstrap/cache

# Set proper permissions
RUN chown -R www-data:www-data \
    /var/www/storage \
    /var/www/bootstrap/cache \
    && chmod -R 775 \
    /var/www/storage \
    /var/www/bootstrap/cache

# Copy and set permissions for entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Switch to www-data user for better security
USER www-data

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=40s --retries=3 \
    CMD php artisan db:show || exit 1

# Use entrypoint script to handle Laravel setup
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Start PHP-FPM
CMD ["php-fpm"]
