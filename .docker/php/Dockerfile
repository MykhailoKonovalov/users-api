FROM php:8.3-fpm

RUN apt-get update && apt-get install --no-install-recommends -y \
    libicu-dev \
    libzip-dev \
    unzip \
    git

RUN docker-php-ext-install intl opcache pdo pdo_mysql

RUN pecl install apcu && docker-php-ext-enable apcu

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN chmod 777 /usr/src --recursive

ARG UNAME=developer
ARG UID=1000
ARG GID=1000
RUN groupadd -g $GID -o $UNAME
RUN useradd -m -u $UID -g $GID -o -s /bin/ash $UNAME
USER $UNAME

WORKDIR /usr/src/app