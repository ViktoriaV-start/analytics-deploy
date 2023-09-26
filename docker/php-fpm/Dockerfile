FROM php:8.2-fpm

ARG timezone

RUN apt-get update && apt-get install -y \
		git \
        curl \
        htop \
        libmemcached-dev \
        cron\
        unzip \
        nano \
        libicu-dev \
        zlib1g-dev \
        libssl-dev \
        pkg-config \
        libzip-dev \
        libpq-dev \
        libssh-dev \
        mailutils  \
    && rm -rf /var/lib/apt/lists/*

RUN set -xe \
    && docker-php-ext-configure \
        intl \
    && docker-php-ext-install \
        intl \
        opcache \
        zip \
        pdo \
        pdo_mysql \
        bcmath \
        sockets \
    && pecl install \
        xdebug \
        redis \
        && rm -rf /tmp/pear \
    && docker-php-ext-enable \
        xdebug \
        redis

COPY php.ini /usr/local/etc/php/php.ini
RUN sed -i -e "s#TIMEZONE#$timezone#g" /usr/local/etc/php/php.ini

#COPY xdebug.ini /tmp/xdebug.ini
#RUN cat /tmp/xdebug.ini >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
#RUN rm /tmp/xdebug.ini

COPY --from=composer:2.3.5 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1

RUN mkdir -p /var/www/.composer && chown -R www-data /var/www/.composer
WORKDIR /data_sfera

RUN curl -fsSL https://deb.nodesource.com/setup_19.x | bash - \
    && apt-get install -y nodejs

COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# MAILHOG
RUN apt-get update &&\
    apt-get install --no-install-recommends --assume-yes --quiet ca-certificates curl git &&\
    rm -rf /var/lib/apt/lists/*

COPY go1.8.3.linux-amd64.tar.gz /usr/local/go1.8.3.linux-amd64.tar.gz
RUN tar -xf /usr/local/go1.8.3.linux-amd64.tar.gz -C /usr/local

ENV PATH /usr/local/go/bin:$PATH
RUN go get github.com/mailhog/mhsendmail
RUN cp /root/go/bin/mhsendmail /usr/bin/mhsendmail
RUN echo 'sendmail_path = /usr/bin/mhsendmail --smtp-addr mailhog:1025' > /usr/local/etc/php/php.ini

ENTRYPOINT ["/entrypoint.sh"]
CMD ["php-fpm"]