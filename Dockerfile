FROM php:8.0-apache

WORKDIR /var/www/html

RUN docker-php-ext-install pdo pdo_mysql mysqli && docker-php-ext-enable mysqli

RUN apt-get update && apt-get upgrade -y

RUN apt-get install zip unzip

RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

RUN a2enmod rewrite

RUN service apache2 restart

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer