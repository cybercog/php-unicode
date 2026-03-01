ARG PHP_VERSION
FROM php:${PHP_VERSION}-cli-alpine

RUN apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        icu-dev \
    && apk add --no-cache icu-libs \
    && docker-php-ext-install intl \
    && apk del .build-deps

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
