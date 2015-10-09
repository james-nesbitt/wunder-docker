FROM       jamesnesbitt/wunder-base
MAINTAINER james.nesbitt@wunderkraut.com

### DRUPALCONSOLE ----------------------------------------------------------

# Install MariaDB
ADD etc/yum.repos.d/mariadb.repo etc/yum.repos.d/mariadb.repo
RUN /usr/bin/yum --assumeyes --verbose install MariaDB-client

# Prepare to use the remi repo for PHP: our .repo file has certain repos enabled=1
ADD root/remi-release-7.rpm /root/remi-release-7.rpm
RUN /usr/bin/rpm -Uvh /root/remi-release-7.rpm
ADD etc/yum.repos.d/remi.repo /etc/yum.repos.d/remi.repo
ADD etc/yum.repos.d/remi-php70.repo /etc/yum.repos.d/remi-php70.repo

# Install php-fpm
RUN /usr/bin/yum install --assumeyes --verbose \
    php-cli php-pdo php-mysql php-pgsql php-ldap php-gd php-mbstring php-mcrypt php-xml \
    php-opcache php-pecl-uploadprogress

# Install some tools that are used for composer and user handling
RUN /usr/bin/yum --assumeyes --verbose install openssl tar git

# Add some php extension configuraiton overrides
ADD etc/php.d/01-defaultdatezone.ini /etc/php.d/01-defaultdatezone.ini
ADD etc/php.d/40-apcu.ini /etc/php.d/40-apcu.ini

# Install blackfire
ADD etc/yum.repos.d/blackfire.repo /etc/yum.repos.d/blackfire.repo
RUN /usr/bin/yum install -y install pygpgme blackfire-php
# Add some php extension configuraiton overrides
ADD etc/php.d/zz-blackfire.ini /etc/php.d/zz-blackfire.ini

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

# Everything else is run as the platform user
USER app

# Install nodejs and npm, which gets used for lots of stuff with SASS/SCSS
#RUN /usr/bin/yum install --assumeyes --verbose nodejs npm
#RUN /usr/bin/npm install gulp -g

# Make sure that the composer items are in path
ENV PATH /app/bin:/app/.composer/vendor/bin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin

# Install Drupal console
RUN /usr/local/bin/composer global require drupal/console:dev-master

# Install DRUSH, which is can be run by drush
RUN /usr/local/bin/composer global require drush/drush:dev-master drush/config-extra

# Get some additional suggested packages
RUN /usr/local/bin/composer global require pear/Console_Color2

# Some usefull env variables
ENV TERM=xterm

WORKDIR "/app/www/active"
ENTRYPOINT ["/app/.composer/vendor/bin/console", "--ansi", "--drupal=/app/www/active"]
CMD "--shell"

### /DRUPALCONSOLE ---------------------------------------------------------------=