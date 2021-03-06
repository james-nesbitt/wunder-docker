FROM       jamesnesbitt/wunder-lampstackplus
MAINTAINER james.nesbitt@wunderkraut.com

### DEVELOPER  ---------------------------------------------------------------

# Install some tools that are usefull for a developer
RUN /usr/bin/yum --assumeyes --verbose install openssl tar git zsh sudo vim

# Get XDebug into the php server
RUN /usr/bin/yum --assumeyes --verbose install php-xdebug
# override the default php.d xdebug.ini
ADD etc/php.d/15-xdebug.ini /etc/php.d/15-xdebug.ini 

# Allow passwordless sudo for the app user
RUN /usr/bin/echo "app        ALL=(ALL)       NOPASSWD: ALL" > /etc/sudoers.d/app

# Install nodejs and npm, which gets used for lots of stuff with SASS/SCSS
RUN /usr/bin/yum install --assumeyes --verbose nodejs npm
RUN /usr/bin/npm install gulp -g

# Install mailcatcher
RUN /usr/bin/yum -y install gcc gcc-c++ sqlite-devel ruby-devel
RUN /usr/bin/gem install mailcatcher
ADD etc/php.d/yy-mailcatcher.ini /etc/php.d/yy-mailcatcher.ini
ADD etc/supervisord.d/mailcatcher.ini /etc/supervisord.d/mailcatcher.ini
EXPOSE 1080

# Install composer which will be used to install drush
RUN /usr/bin/yum install --assumeyes --verbose composer

# Everything else is run as the platform user
USER app

# Add composer paths and app/bin to path
ENV PATH /app/bin:/app/.composer/vendor/bin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin

# Install DRUSH, which is a requirement for platform
RUN /usr/bin/composer global require drush/drush:dev-master drush/config-extra

# Install Drupal console
RUN /usr/bin/composer global require drupal/console
# RUN cd /app/bin && curl -LSs http://drupalconsole.com/installer | php && mv console.phar drupal

# Install platform.sh cli
RUN /usr/bin/composer global require platformsh/cli:@stable
# RUN /usr/bin/composer global require "commerceguys/platform-cli=1.*"

# Configure Platform CLI with this ENV var
ENV PLATFORMSH_CLI_API_TOKEN %PLATFORMSH_CLI_API_TOKEN

# Set up some folders for the tools that we just used
RUN /usr/bin/mkdir /app/.platformsh && \
    /usr/bin/chown -R app:app /app/.platformsh && \
    /usr/bin/mkdir /app/.drush && \
    /usr/bin/chown -R app:app /app/.drush && \
    /usr/bin/mkdir /app/.ssh && \
    /usr/bin/chown -R app:app /app/.ssh

# Some usefull env variables
ENV TERM=xterm

#CMD ["/bin/bash"]

### /DEVELOPER ---------------------------------------------------------------
### James Developer ----------------------------------------------------------

USER app

# Make the app user use the zsh shell with oh-my-zsh installed
RUN git clone git://github.com/robbyrussell/oh-my-zsh.git ~/.oh-my-zsh
ADD .zshrc /app/.zshrc

USER root

# Add the nginx user to various groups that the user may be a part of
#  - on OSX users are typically in the staff group, which translates into
#  - the centos ftp group.  This is behaviour from using boot2docker
RUN /usr/sbin/usermod -a -G ftp,games nginx

# Change the default shell to zsh, and add a nice zsh prompt
RUN /usr/sbin/usermod -s /bin/zsh app && \
    chown -R app:app /app/.zshrc /app/.oh-my-zsh

#USER app
#CMD ["/bin/zsh"]

### /James Developer ---------------------------------------------------------
