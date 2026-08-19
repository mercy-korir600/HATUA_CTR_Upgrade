FROM --platform=linux/amd64 php:5.6-apache

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=Africa/Nairobi

RUN docker-php-ext-install pdo_mysql mbstring \
    && a2enmod rewrite

# CakePdf (app/Config/core.php) shells out to wkhtmltopdf at /usr/local/bin/wkhtmltopdf to
# generate SAE/CIOMS/application PDFs - install it and the fonts/libs it needs to render, then
# symlink it to the path the app expects (apt puts the binary in /usr/bin).
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        wkhtmltopdf \
        fontconfig \
        xfonts-75dpi \
        xfonts-base \
    && rm -rf /var/lib/apt/lists/* \
    && ln -sf /usr/bin/wkhtmltopdf /usr/local/bin/wkhtmltopdf

WORKDIR /var/www/html

COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
COPY docker/sudo-shim.sh /usr/local/bin/sudo
COPY . /var/www/html

RUN chmod +x /usr/local/bin/sudo \
    && printf 'date.timezone=%s\n' "$TZ" > /usr/local/etc/php/conf.d/date-timezone.ini \
    && chown -R www-data:www-data /var/www/html/app/tmp \
    && chmod -R 775 /var/www/html/app/tmp

EXPOSE 80 

CMD ["apachectl", "-D", "FOREGROUND"]