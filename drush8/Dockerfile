FROM        jamesnesbitt/wunder-base
MAINTAINER  james.nesbitt@wunderkraut.com

### DRUSH ---------------------------------------------------------------

# Prepare to use the remi repo for PHP: our .repo file has certain repos enabled=1
ADD root/remi-release-7.rpm /root/remi-release-7.rpm
RUN /usr/bin/rpm -Uvh /root/remi-release-7.rpm
ADD etc/yum.repos.d/remi.repo /etc/yum.repos.d/remi.repo
ADD etc/yum.repos.d/remi-php70.repo /etc/yum.repos.d/remi-php70.repo

# Install php-fpm - currently php7-rc6
RUN /usr/bin/yum install --assumeyes --verbose \
    php-cli php-opcache php-pdo php-mysql php-pgsql \
    php-ldap php-gd php-mbstring php-mcrypt php-xml \
    php-pecl-apcu php-pecl-yaml php-pecl-uploadprogress php-pecl-zip

# Add some php extension configuraiton overrides
ADD etc/php.d/01-defaultdatezone.ini /etc/php.d/01-defaultdatezone.ini
ADD etc/php.d/40-apcu.ini /etc/php.d/40-apcu.ini

# Install MariaDB
ADD etc/yum.repos.d/mariadb.repo etc/yum.repos.d/mariadb.repo
RUN /usr/bin/yum --assumeyes --verbose install MariaDB-client

# Install some tools that are used for composer and user handling
RUN /usr/bin/yum --assumeyes --verbose install openssl tar git

# Install composer which will be used to install drush
RUN /usr/bin/yum install --assumeyes --verbose composer

# make sure that the nginx user is created, and has access to app user files
# nginx:x:999:999:Nginx web server:/var/lib/nginx:/sbin/nologin
RUN /usr/sbin/adduser --home-dir /var/lib/nginx --uid 999 --user-group --shell /bin/nologin --password "`openssl rand -base64 32 | openssl passwd -1 -stdin`" --comment "Nginx web server" nginx && \
    /usr/bin/gpasswd -a nginx app

# Set up some folders for the tools that we may use
RUN /usr/bin/mkdir /app/.drush && \
    /usr/bin/chown -R app:app /app/.drush && \
    /usr/bin/mkdir /app/.composer && \
    /usr/bin/chown -R app:app /app/.composer && \
    /usr/bin/mkdir /app/.ssh && \
    /usr/bin/chown -R app:app /app/.ssh

# Everything else is run as the application user
USER app

# Make sure that the composer items are in path
ENV PATH /app/bin:/app/.composer/vendor/bin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin

# Install DRUSH
RUN /usr/bin/composer global require drush/drush:dev-master drush/config-extra

ENTRYPOINT ["/app/.composer/vendor/bin/drush"]
CMD ["status"]

### /DRUSH ---------------------------------------------------------------
