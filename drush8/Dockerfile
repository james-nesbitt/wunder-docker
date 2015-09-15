FROM        jamesnesbitt/wunder-base
MAINTAINER  james.nesbitt@wunderkraut.com

### DRUSH ---------------------------------------------------------------

# Install some tools that are usefull for checking out drush
RUN /usr/bin/yum install -y openssl tar git

# Install a mysql-client, which drush sometimes needs
ADD etc/yum.repos.d/mariadb.repo etc/yum.repos.d/mariadb.repo
RUN /usr/bin/yum --assumeyes --verbose install mariadb

# Add the zend php repo
ADD etc/yum.repos.d/zend-php7.repo /etc/yum.repos.d/zend-php7.repo
RUN /usr/bin/yum --assumeyes --verbose install php7-nightly
RUN ln -s /usr/local/php7/bin/php /usr/local/bin/php

# Install composer which will be used to install drush
RUN /usr/bin/curl -sS https://getcomposer.org/installer | /usr/local/php7/bin/php -- --filename=composer --install-dir=/usr/local/bin

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
