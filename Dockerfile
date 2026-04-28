FROM --platform=linux/amd64 php:5.6-apache

ENV DEBIAN_FRONTEND=noninteractive

RUN docker-php-ext-install pdo_mysql mbstring \
    && a2enmod rewrite

WORKDIR /var/www/html

COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html/app/tmp \
    && chmod -R 775 /var/www/html/app/tmp

EXPOSE 80

CMD ["apachectl", "-D", "FOREGROUND"]
