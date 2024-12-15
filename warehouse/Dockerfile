FROM php:8.3-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    libpq-dev \
    libonig-dev \
    supervisor \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring zip exif pcntl


RUN apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www

COPY --chown=www-data:www-data . /var/www

RUN mkdir -p /var/log/supervisor

COPY ./docker-compose/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

USER root
RUN chmod -R 777 /var/log/supervisor
RUN chown -R www-data:www-data /etc/supervisor /var/log/supervisor

USER www-data

EXPOSE 9000

#CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
CMD ["php-fpm"]
