FROM        jamesnesbitt/wunder-base
MAINTAINER  james.nesbitt@wunderkraut.com

### PLATFORMSH ----------------------------------------------------------------

# Install some base tools
RUN /usr/bin/yum --assumeyes --verbose install tar gunzip git

# Install PHP
RUN /usr/bin/yum install --assumeyes --verbose php-cli php-common \
    php-pecl-apc php-pdo php-mysql php-pgsql php-pecl-mongo \
    php-ldap php-sqlite php-pecl-memcache php-pecl-memcached php-gd php-mbstring \
    php-mcrypt php-xml php-pecl-yaml

# Install Composer
RUN /usr/bin/curl -sS https://getcomposer.org/installer | php -- --filename=composer --install-dir=/usr/local/bin

# Create the composer build path
RUN /usr/bin/mkdir /composer && \
    /usr/bin/chown app:app /composer

# Everything else is run as the platform user
USER app

# Make sure that the composer items are in path
ENV COMPOSER_HOME /composer
ENV PATH="/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/composer/vendor/bin"

# Install DRUSH, which is a requiremnt for platform
RUN /usr/local/bin/composer global require drush/drush:dev-master
ENV PLATFORMSH_CLI_DRUSH=/composer/vendor/bin/drush
# RUN /usr/local/bin/composer global require drush/config-extra

# Install platform.sh cli
RUN /usr/local/bin/composer global require platformsh/cli:@stable
# RUN /usr/local/bin/composer global require "commerceguys/platform-cli=1.*"

# Some additional Platform ENV Variables
ENV PLATFORMSH_CLI_DEBUG=0
ENV PLATFORMSH_CLI_DISABLE_CACHE=0

ENTRYPOINT ["/composer/vendor/bin/platform"]
CMD ["--shell"]

### /PLATFORMSH ---------------------------------------------------------------
