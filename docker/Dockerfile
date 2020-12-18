FROM php:7.4-fpm-alpine

ENV APP_HOME /app

# Lib
RUN apk update
RUN apk --no-cache add pcre-dev ${PHPIZE_DEPS} \
    supervisor \
    nginx \
    unixodbc-dev \
    zlib-dev \
    cyrus-sasl-dev \
    openssh \
    git

RUN echo "root:Docker!" | chpasswd
RUN /usr/bin/ssh-keygen -A
COPY docker/conf/sshd_config /etc/ssh/

# Nginx
RUN mkdir -p /run/nginx \
&& chown -Rf www-data:www-data /var/lib/nginx
COPY docker/conf/nginx.conf /etc/nginx/nginx.conf

# PHP Extensions
RUN pecl install xdebug \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && docker-php-ext-enable opcache xdebug

# Composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

RUN mkdir -p /var/log/supervisor

COPY docker/conf/supervisord.conf /etc/
COPY docker/conf/php.ini /usr/local/etc/php/
COPY --chown=www-data:www-data . $APP_HOME
WORKDIR $APP_HOME

RUN composer install

# Laravel set up
COPY .env.example .env
RUN php artisan key:generate

EXPOSE 2222 80

CMD ["/usr/bin/supervisord", "-n"]
