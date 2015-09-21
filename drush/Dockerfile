FROM        jamesnesbitt/wunder-base
MAINTAINER  james.nesbitt@wunderkraut.com

### DRUSH ---------------------------------------------------------------

# Install some tools that are usefull for checking out drush
RUN /usr/bin/yum install -y openssl tar git

# Install a mysql-client, which drush sometimes needs
# @note currently this uses mariadb 5.x, wheras our db image uses 10.x (an upgrade would require using the yum mariadb repo)
RUN /usr/bin/yum install --assumeyes --verbose mariadb

# Install PHP which will be used for drush
RUN /usr/bin/yum install --assumeyes --verbose php-cli php-opcache \
    php-pecl-apc php-pdo php-mysql php-pgsql php-pecl-mongo \
    php-ldap php-sqlite php-pecl-memcache php-pecl-memcached php-gd php-mbstring \
    php-mcrypt php-xml php-pecl-yaml

# Install composer which will be used to install drush
RUN /usr/bin/curl -sS https://getcomposer.org/installer | php -- --filename=composer --install-dir=/usr/local/bin

# Install Drush
RUN /usr/bin/git clone -b master https://github.com/drush-ops/drush.git /usr/local/src/drush && \
    /usr/bin/ln -s /usr/local/src/drush/drush /usr/local/bin/drush && \
    /usr/local/bin/composer --working-dir=/usr/local/src/drush  install

# add drush settings & aliases
RUN /usr/bin/mkdir /etc/drush
RUN /usr/bin/mkdir /app/.drush/ && \
    /usr/bin/chown -R app:app /app/.drush

USER app

# set up some env variables to make using easier.
ENV TERM="xterm"

ENTRYPOINT ["/usr/local/bin/drush"]
CMD ["status"]

### /DRUSH ---------------------------------------------------------------
