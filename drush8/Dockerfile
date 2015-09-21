FROM        jamesnesbitt/wunder-base
MAINTAINER  james.nesbitt@wunderkraut.com

### DRUSH ---------------------------------------------------------------

# Prepare to use the remi repo for PHP: our .repo file has certain repos enabled=1
ADD root/remi-release-7.rpm /root/remi-release-7.rpm
RUN /usr/bin/rpm -Uvh /root/remi-release-7.rpm
ADD etc/yum.repos.d/remi.repo /etc/yum.repos.d/remi.repo
ADD etc/yum.repos.d/remi-php70.repo /etc/yum.repos.d/remi-php70.repo
ADD etc/yum.repos.d/mariadb.repo etc/yum.repos.d/mariadb.repo

# Install php-fpm
RUN /usr/bin/yum install --assumeyes --verbose \
    php-cli php-pdo php-mysql php-pgsql php-ldap php-gd php-mbstring php-mcrypt php-xml \
    php-opcache php-pecl-uploadprogress

# Install MariaDB
RUN /usr/bin/yum --assumeyes --verbose install MariaDB-client

# Install composer which will be used to install drush
RUN /usr/bin/curl -sS https://getcomposer.org/installer | php -- --filename=composer --install-dir=/usr/local/bin

# Install Drush
RUN /usr/bin/yum --assumeyes --verbose install git
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
