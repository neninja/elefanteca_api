FROM php:8.0-apache

WORKDIR /var/www/html

RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite

RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini
#RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

RUN apt-get update; \
    apt-get install -y libpq5 libpq-dev libzip-dev zip; \
    pecl install xdebug; \
    docker-php-ext-install pdo pdo_pgsql zip; \
    docker-php-ext-enable xdebug; \
    echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/xdebug.ini; \
    apt-get autoremove --purge -y libpq-dev; \
    apt-get clean ; \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
