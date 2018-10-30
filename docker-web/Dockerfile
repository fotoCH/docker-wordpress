FROM php:7.2-apache
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        less \
    && docker-php-ext-install mysqli \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
	  && docker-php-ext-install -j$(nproc) gd \
    && a2enmod rewrite \
    && a2enmod headers \
    && curl -o /usr/local/bin/_wp-cli.phar https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
    && echo "/usr/local/bin/_wp-cli.phar --allow-root" > /usr/local/bin/wp \
    && chmod +x /usr/local/bin/_wp-cli.phar \
    && chmod +x /usr/local/bin/wp 
