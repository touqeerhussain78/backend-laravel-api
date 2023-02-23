ARG IMAGE=php:8.1-alpine

FROM ${IMAGE} AS base

RUN apk --no-cache add pcre-dev ${PHPIZE_DEPS} \
	&& apk add --no-cache git

RUN pecl install redis \
    && rm -rf /tmp/pear

RUN docker-php-ext-install pdo_mysql pcntl \
    && docker-php-ext-enable redis

RUN apk del pcre-dev ${PHPIZE_DEPS}

WORKDIR /app
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

FROM base as development

COPY --chown=www-data:www-data composer.* /app/

ARG COMPOSER_AUTH

RUN composer install --no-interaction --no-ansi --no-scripts

COPY --chown=www-data:www-data . /app

RUN mkdir -p /app/bootstrap/cache \
    && chmod 777 -R /app/bootstrap/cache

RUN cp $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

FROM development as production

RUN composer install --optimize-autoloader --prefer-dist --no-dev --no-interaction --no-ansi --no-suggest
RUN cp $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN rm -f auth.json

RUN php artisan storage:link
RUN php artisan route:cache \
    && php artisan view:cache
