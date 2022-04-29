FROM php:8.0-fpm

RUN apt-get update; \
    apt-get install -y libpq5 libpq-dev libzip-dev zip; \
    pecl install xdebug; \
    docker-php-ext-install pdo pdo_mysql zip sysvsem; \
    docker-php-ext-enable xdebug; \
    echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/xdebug.ini; \
    apt-get autoremove --purge -y libpq-dev; \
    apt-get clean ; \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
