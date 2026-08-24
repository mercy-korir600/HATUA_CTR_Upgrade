FROM --platform=linux/amd64 php:5.6-apache

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=Africa/Nairobi

RUN docker-php-ext-install pdo_mysql mbstring \
    && a2enmod rewrite

WORKDIR /var/www/html

COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
COPY docker/sudo-shim.sh /usr/local/bin/sudo
COPY . /var/www/html

# No php.ini is enabled by default on the official php:5.6-apache image, so
# PHP falls back to a 128M memory_limit - too thin for this app's
# Application::view() (large eager-loaded association trees) plus
# DebugKit's per-request toolbar cache. 512M gives real headroom; raise
# further here if a future page still exhausts it.
RUN chmod +x /usr/local/bin/sudo \
    && printf 'date.timezone=%s\n' "$TZ" > /usr/local/etc/php/conf.d/date-timezone.ini \
    && printf 'memory_limit=512M\n' > /usr/local/etc/php/conf.d/memory-limit.ini \
    && chown -R www-data:www-data /var/www/html/app/tmp \
    && chmod -R 775 /var/www/html/app/tmp

EXPOSE 80

CMD ["apachectl", "-D", "FOREGROUND"]
