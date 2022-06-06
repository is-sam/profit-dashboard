FROM php:8.1-apache

RUN apt update

# php extensions installer
RUN apt-get install -y libicu-dev && \
    docker-php-ext-install intl pdo_mysql

# install composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# install nodejs & npm
RUN apt install -y nodejs npm zip unzip

# install localtunnel
RUN npm install -g localtunnel-https

# apache.conf
# COPY ./apache.conf /etc/apache2/sites-available/000-default.conf
RUN sed -i "s/Listen 80/Listen ${PORT:-8000}/g" /etc/apache2/ports.conf
RUN a2enmod rewrite ssl headers proxy

# install Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony

RUN chown -R www-data:www-data /var/www

WORKDIR /var/www

CMD ["tail", "-f", "/dev/null"]
