FROM php:7.2-fpm-stretch

LABEL maintainer="Vinicius Mattos vinimattos.rj@gmail.com"

#Instaling my-sql driver
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libpq-dev libldap2-dev mysql-client zip libpq-dev libzip4 git wget vim\
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip

RUN docker-php-ext-install pcntl

#Install composer
COPY --from=composer:1 /usr/bin/composer /usr/bin/composer

#Create a new directory to run our app.
RUN mkdir -p /var/www/html/

#Set the new directory as our working directory
WORKDIR /var/www/html/

#Copy all the content to the working directory
COPY . /var/www/html/

#Newrelic installation command
ENV NEWRELIC_VERSION 9.12.0.268
ENV NEWRELIC_NAME newrelic-php5-${NEWRELIC_VERSION}-linux

RUN set -ex; \
    wget -O /tmp/${NEWRELIC_NAME}.tar.gz https://download.newrelic.com/php_agent/archive/${NEWRELIC_VERSION}/${NEWRELIC_NAME}.tar.gz; \
    cd /tmp/; \
    tar -xzf ${NEWRELIC_NAME}.tar.gz; \
    export NR_INSTALL_SILENT=1; \
    export NR_INSTALL_USE_CP_NOT_LN=1; \
    ${NEWRELIC_NAME}/newrelic-install install; \
    sed -i \
        -e 's/;newrelic.daemon.app_connect_timeout =.*/newrelic.daemon.app_connect_timeout=15s/' \
        -e 's/;newrelic.daemon.start_timeout =.*/newrelic.daemon.start_timeout=5s/' \
        /usr/local/etc/php/conf.d/newrelic.ini


RUN apt-get install libc6

#Install composer dependecies
RUN composer install --no-dev
RUN mkdir -p /var/www/html/storage/ && \
    chmod -R 777 /var/www/html/storage


# Create user
ENV USER=app USER_ID=1234 USER_GID=1234

RUN groupadd --gid "${USER_GID}" "${USER}" && \
    useradd \
        --uid ${USER_ID} \
        --gid ${USER_GID} \
        --create-home \
        --shell /bin/bash \
    ${USER}
    
# Set user permissions
# RUN mkdir -p /var/www/html/storage/framework/sessions && \
#     mkdir -p /var/www/html/storage/framework/views && \
#     mkdir -p /var/www/html/storage/framework/cache && \
#     mkdir -p /var/www/html/storage/framework/cache/data && \
#     mkdir -p /var/www/html/storage/framework/testing && \
#     mkdir -p /var/www/html/storage/app/public && \
#     mkdir -p /var/www/html/storage/monitoroReports && \
#     chmod -R 777 /var/log/newrelic/newrelic-daemon.log && \
#     chmod -R 777 /var/www/html/storage && \
#     chown -R ${USER}:${USER_GID} /var/www/html/

RUN sed -i "s/www-data/$USER/" /usr/local/etc/php-fpm.d/www.conf

USER ${USER}

#Our app runs on port 9000. Expose it!
EXPOSE 9000

#Run the application.
CMD ["/var/www/html/start_server"]