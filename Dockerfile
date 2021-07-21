FROM php:8.0.6-apache

# set libs 
RUN apt-get update \
	&& apt-get install -y libxml2-dev git zip unzip libzip4\
  # add your dependencies
	&& CFLAGS="-I/usr/src/php" docker-php-ext-install soap mysqli;
# Configure PHP for Cloud Run.
# Precompile PHP code with opcache.
RUN docker-php-ext-install -j "$(nproc)" opcache
RUN set -ex; \
  { \
    echo "; Cloud Run enforces memory & timeouts"; \
    echo "memory_limit = -1"; \
    echo "max_execution_time = 0"; \
    echo "; File upload at Cloud Run network limit"; \
    echo "upload_max_filesize = 32M"; \
    echo "post_max_size = 32M"; \
    echo "; Configure Opcache for Containers"; \
    echo "opcache.enable = On"; \
    echo "opcache.validate_timestamps = Off"; \
    echo "; Configure Opcache Memory (Application-specific)"; \
    echo "opcache.memory_consumption = 32"; \
  } > "$PHP_INI_DIR/conf.d/cloud-run.ini"

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer;
# Copy in custom code from the host machine.
WORKDIR /var/www/
COPY app/ ./app
COPY composer.json ./composer.json
COPY config/* ./app/
RUN composer install;

RUN rm -R ./html && mv ./app ./html

# Use the PORT environment variable in Apache configuration files.
# https://cloud.google.com/run/docs/reference/container-contract#port
RUN sed -i 's/80/80/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Configure PHP env.
# https://github.com/docker-library/docs/blob/master/php/README.md#configuration
#RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# clean libs 
RUN apt-get purge -y --auto-remove libxml2-dev zip unzip \
	&& rm -r /var/lib/apt/lists/*

RUN a2enmod actions && \
    a2enmod rewrite && \
    a2enmod headers && \
    a2enmod setenvif && \
    a2enmod mime && \
    a2enmod autoindex && \
    a2enmod authz_core && \
    a2enmod deflate && \
    a2enmod expires;

RUN /etc/init.d/apache2 restart