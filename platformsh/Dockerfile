FROM        jamesnesbitt/wunder-base
MAINTAINER  james.nesbitt@wunderkraut.com

### PLATFORMSH ----------------------------------------------------------------

# Prepare to use the remi repo for PHP: our .repo file has certain repos enabled=1
ADD root/remi-release-7.rpm /root/remi-release-7.rpm
RUN /usr/bin/rpm -Uvh /root/remi-release-7.rpm
ADD etc/yum.repos.d/remi.repo /etc/yum.repos.d/remi.repo
ADD etc/yum.repos.d/remi-php70.repo /etc/yum.repos.d/remi-php70.repo

RUN /usr/bin/yum install --assumeyes --verbose \
    php-cli php-opcache php-pdo php-mysql php-pgsql php-ldap \
    php-gd php-mbstring php-mcrypt php-xml php-soap php-json \
    php-pecl-uploadprogress php-pecl-apcu php-pecl-geoip

# Add some php extension configuraiton overrides
ADD etc/php.d/01-defaultdatezone.ini /etc/php.d/01-defaultdatezone.ini
ADD etc/php.d/40-apcu.ini /etc/php.d/40-apcu.ini

# Install MariaDB
ADD etc/yum.repos.d/mariadb.repo etc/yum.repos.d/mariadb.repo
RUN /usr/bin/yum --assumeyes --verbose install MariaDB-client

# Install some tools that are used for composer and user handling
RUN /usr/bin/yum --assumeyes --verbose install openssl tar git

# Install composer which will be used to install drush
RUN /usr/bin/curl -sS https://getcomposer.org/installer | php -- --filename=composer --install-dir=/usr/local/bin

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

# set up some env variables to make using easier.
ENV TERM="xterm"

# Install DRUSH, which is a requiremnt for platform
RUN /usr/local/bin/composer global require drush/drush:dev-master drush/config-extra
ENV PLATFORMSH_CLI_DRUSH=/app/.composer/vendor/bin/drush

# Install platform.sh cli
RUN /usr/local/bin/composer global require platformsh/cli

# Some additional Platform ENV Variables
ENV PLATFORMSH_CLI_DEBUG=0
ENV PLATFORMSH_CLI_DISABLE_CACHE=0

ENTRYPOINT ["/app/.composer/vendor/bin/platform"]
CMD ["--shell"]

### /PLATFORMSH ---------------------------------------------------------------
