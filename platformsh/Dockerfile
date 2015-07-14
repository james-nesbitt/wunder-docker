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
RUN rpm --import /etc/pki/rpm-gpg/RPM-GPG-KEY-CentOS-7 && \
    cd /root && \
    curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

# Make sure that the composer items are in path
ENV COMPOSER_HOME /composer
RUN echo 'export PATH=$PATH:$COMPOSER_HOME/.composer/vendor/bin' >> /etc/profile.d/composer.sh

RUN /usr/bin/mkdir /composer && \
    /usr/bin/chown app:app /composer

# Everything else is run as the platform user
USER app

# Install DRUSH, which is a requiremnt for platform
RUN composer global require drush/drush:dev-master
# RUN composer global require drush/config-extra

# Install platform.sh cli
RUN composer global require platformsh/cli:@stable
# RUN composer global require "commerceguys/platform-cli=1.*"

ENTRYPOINT "/composer/vendor/bin/platform"

### /PLATFORMSH ---------------------------------------------------------------
