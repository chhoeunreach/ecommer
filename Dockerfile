FROM php:8.2-fpm-bookworm

# Pinned to 8.2 (not 8.3, unlike ultimate's image) — lcobucci/clock, a
# transitive dependency of genealabs/laravel-sign-in-with-apple, hard-caps
# at "~8.1.0 || ~8.2.0" and fails `composer install` on 8.3.
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev nginx \
    && docker-php-ext-install pdo_mysql mysqli mbstring exif pcntl bcmath gd zip \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY docker/php/uploads.ini /usr/local/etc/php/conf.d/uploads.ini

COPY . /var/www
WORKDIR /var/www

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/testing storage/framework/views storage/logs storage/debugbar bootstrap/cache public/uploads public/logs && \
    chmod -R 775 storage bootstrap/cache public/uploads public/logs && \
    composer install --no-dev --no-interaction --optimize-autoloader

RUN printf "# Managed by Kubernetes env vars - see deployment-active-commerce.yaml. Do not put secrets here.\n" > /var/www/.env && \
    chown www-data:www-data /var/www/.env && \
    chown -R www-data:www-data storage bootstrap/cache public/uploads public/logs && \
    chown -R www-data:www-data app/Providers app/Http/Controllers public resources/views && \
    chown www-data:www-data /var/www && \
    rm -f /etc/nginx/sites-enabled/*; \
    php artisan view:cache 2>/dev/null; \
    php artisan event:cache 2>/dev/null; \
    rm -rf /var/www/storage/framework/cache/data 2>/dev/null; \
    true

COPY docker/php/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

COPY nginx/default.conf /etc/nginx/sites-enabled/default

RUN sed -i 's/^pm.max_children = 5/pm.max_children = 16/' /usr/local/etc/php-fpm.d/www.conf && \
    sed -i 's/^pm.start_servers = 2/pm.start_servers = 4/' /usr/local/etc/php-fpm.d/www.conf && \
    sed -i 's/^pm.min_spare_servers = 1/pm.min_spare_servers = 2/' /usr/local/etc/php-fpm.d/www.conf && \
    sed -i 's/^pm.max_spare_servers = 3/pm.max_spare_servers = 8/' /usr/local/etc/php-fpm.d/www.conf && \
    sed -i 's/^;pm.max_requests = 500/pm.max_requests = 500/' /usr/local/etc/php-fpm.d/www.conf && \
    sed -i 's/^;request_terminate_timeout = 0/request_terminate_timeout = 300s/' /usr/local/etc/php-fpm.d/www.conf && \
    sed -i 's/^[[:space:]]*worker_connections 768/worker_connections 1024/' /etc/nginx/nginx.conf

EXPOSE 80
CMD ["start.sh"]
