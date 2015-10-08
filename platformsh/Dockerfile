FROM        jamesnesbitt/wunder-base
MAINTAINER  james.nesbitt@wunderkraut.com

### PLATFORMSH ----------------------------------------------------------------

# Install some base tools
RUN /usr/bin/yum --assumeyes --verbose install tar gunzip git

# Prepare to use the remi repo for PHP: our .repo file has certain repos enabled=1
ADD root/remi-release-7.rpm /root/remi-release-7.rpm
RUN /usr/bin/rpm -Uvh /root/remi-release-7.rpm
# Overwrite the default remi repos, to enable the remi and remi7 by default
ADD etc/yum.repos.d/remi.repo /etc/yum.repos.d/remi.repo
ADD etc/yum.repos.d/remi-php70.repo /etc/yum.repos.d/remi-php70.repo

# Install php-fpm
RUN /usr/bin/yum install --assumeyes --verbose \
    php-cli php-opcache php-pdo php-mysql \
    php-gd php-mbstring php-mcrypt php-xml php-soap php-json \
    php-pecl-apcu php-pecl-geoip

# Install Composer
RUN /usr/bin/curl -sS https://getcomposer.org/installer | php -- --filename=composer --install-dir=/usr/local/bin

# Create the composer build path
RUN /usr/bin/mkdir /composer && \
    /usr/bin/chown app:app /composer

# Everything else is run as the platform user
USER app

# Install DRUSH, which is a requiremnet for platform
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
