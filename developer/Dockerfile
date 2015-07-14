FROM        jamesnesbitt/wunder-base
MAINTAINER  james.nesbitt@wunderkraut.com

### DEVELOPER  ---------------------------------------------------------------

# Install some tools that are usefull for a developer
RUN /usr/bin/yum install -y openssl tar git zsh sudo vim

# Set up some stuff for the app user
RUN /usr/bin/echo "app        ALL=(ALL)       NOPASSWD: ALL" > /etc/sudoers.d/app

# Install a mysql-client
RUN /usr/bin/yum install --assumeyes --verbose mariadb

# Install PHP which will be used for blackfire and drush
RUN /usr/bin/yum install --assumeyes --verbose php-cli php-common \
    php-pecl-apc php-pdo php-mysql php-pgsql php-pecl-mongo \
    php-ldap php-sqlite php-pecl-memcache php-pecl-memcached php-gd php-mbstring \
    php-mcrypt php-xml php-pecl-yaml

# Install composer which will be used to install drush
RUN /usr/bin/curl -sS https://getcomposer.org/installer | php -- --filename=composer --install-dir=/usr/local/bin

# Install blackfire
ADD etc/yum.repos.d/blackfire.repo /etc/yum.repos.d/blackfire.repo
RUN /usr/bin/yum install -y install pygpgme blackfire-php
RUN /usr/bin/sed -i "s,unix:///var/run/blackfire/agent.sock,tcp://blackfire.app:8707," /etc/php.d/zz-blackfire.ini

# Install Drush
ADD .drush /app/.drush
RUN /usr/bin/chown -R app:app /app/.drush && \
    /usr/bin/git clone -b 7.x https://github.com/drush-ops/drush.git /usr/local/src/drush && \
    /usr/bin/ln -s /usr/local/src/drush/drush /usr/bin/drush && \
    /usr/local/bin/composer --working-dir=/usr/local/src/drush  install

# Install PlatformSH CLIE
RUN /usr/bin/git clone -b master https://github.com/platformsh/platformsh-cli /usr/local/src/platform && \
    /usr/bin/ln -s /usr/local/src/platform/platform /usr/local/bin/platform && \
    /usr/local/bin/composer --working-dir=/usr/local/src/platform install

# Some usefull env variables
ENV TERM=xterm

CMD ["/bin/bash"]

### /DEVELOPER ---------------------------------------------------------------

### James Developer ----------------------------------------------------------

# Add the scripts folder
ADD scripts /app/scripts
RUN /usr/bin/chown -R app:app /app/scripts

# Make the app user use the zsh shell with oh-my-zsh installed
ADD .oh-my-zsh /app/.oh-my-zsh
ADD .zshrc /app/.zshrc
RUN /usr/sbin/usermod -s /bin/zsh app && \
    chown -R app:app /app/.zshrc /app/.oh-my-zsh

CMD ["/bin/zsh"]

### /James Developer ---------------------------------------------------------
