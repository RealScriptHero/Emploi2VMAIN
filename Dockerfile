FROM php:8.3-fpm-alpine

# Install system dependencies with all required build tools and libraries
RUN apk add --no-cache \
    curl \
    git \
    zip \
    unzip \
    ca-certificates \
    nginx \
    supervisor \
    libpng \
    libjpeg-turbo \
    freetype \
    oniguruma \
    libxml2 && \
    apk add --no-cache --virtual .build-deps \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    libxml2-dev \
    zlib-dev

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) \
    gd \
    pdo \
    pdo_mysql \
    mbstring \
    zip \
    xml \
    opcache && \
    apk del --no-cache .build-deps

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy application files
COPY . /app

# Set permissions
RUN chown -R www-data:www-data /app \
    && chmod -R 755 /app \
    && chmod -R 775 /app/storage \
    && chmod -R 775 /app/bootstrap/cache

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --prefer-dist --no-interaction

# Generate caches
RUN php artisan config:cache \
    && php artisan view:cache \
    && php artisan route:cache

# Setup Nginx
RUN mkdir -p /run/nginx
COPY nginx.conf /etc/nginx/nginx.conf

# Setup Supervisor
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Create necessary directories
RUN mkdir -p /var/log/supervisor

EXPOSE 8000

HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD curl -f http://localhost:${PORT:-8000}/ || exit 1

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
