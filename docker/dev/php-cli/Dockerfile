FROM php:8.3-cli-alpine

RUN apk update && apk upgrade

#RUN apk --no-cache add autoconf g++ make libzip-dev zip phpize && rm -rf /tmp/pear; apk del autoconf g++ make
RUN apk --no-cache add \
    autoconf \
    build-base \
    libzip-dev \
    zip \
    && rm -rf /var/cache/apk/*


RUN apk add libpq-dev
RUN apk add icu-dev

RUN docker-php-ext-configure intl

RUN docker-php-ext-install pdo pdo_pgsql pgsql intl

RUN pecl install redis \
    && docker-php-ext-enable redis

RUN rm -rf /tmp/pear

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

COPY ./dev/php/conf.d /usr/local/etc/php/conf.d


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --quiet

RUN addgroup -g 1000 app && adduser -u 1000 -G app -s /bin/sh -D app

WORKDIR /app

USER app
