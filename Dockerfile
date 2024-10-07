FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql
WORKDIR /var/www/html
COPY . .
RUN chown -R www-data:www-data /var/www/html
COPY php.ini /usr/local/etc/php/
EXPOSE 80