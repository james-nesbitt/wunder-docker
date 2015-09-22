FROM       jamesnesbitt/wunder-lampstackplus
MAINTAINER james.nesbitt@wunderkraut.com

### DEVELOPER  ---------------------------------------------------------------

# Install some tools that are usefull for a developer
RUN /usr/bin/yum --assumeyes --verbose install openssl tar git zsh sudo vim

# Get XDebug into the php server
RUN /usr/bin/yum --assumeyes --verbose install php-xdebug

# Set up some stuff for the app user
RUN /usr/bin/echo "app        ALL=(ALL)       NOPASSWD: ALL" > /etc/sudoers.d/app

# Install nodejs and npm, which gets used for lots of stuff with SASS/SCSS
RUN /usr/bin/yum install --assumeyes --verbose nodejs npm
RUN /usr/bin/npm install gulp -g

# Install composer which will be used to install drush
RUN /usr/bin/curl -sS https://getcomposer.org/installer | php -- --filename=composer --install-dir=/usr/local/bin

# Everything else is run as the platform user
USER app

# Add composer paths and app/bin to path
ENV PATH /app/bin:/app/.composer/vendor/bin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin

# Install DRUSH, which is a requiremnt for platform
RUN composer global require drush/drush:dev-master
RUN composer global require drush/config-extra

# Install Drupal console
RUN cd /app/bin && curl -LSs http://drupalconsole.com/installer | php && mv console.phar drupal

# Install platform.sh cli
RUN composer global require platformsh/cli:@stable
# RUN composer global require "commerceguys/platform-cli=1.*"

# Some usefull env variables
ENV TERM=xterm

#CMD ["/bin/bash"]

### /DEVELOPER ---------------------------------------------------------------
### James Developer ----------------------------------------------------------

USER app

# Make the app user use the zsh shell with oh-my-zsh installed
ADD .oh-my-zsh /app/.oh-my-zsh
ADD .zshrc /app/.zshrc

USER root

RUN /usr/sbin/usermod -s /bin/zsh app && \
    chown -R app:app /app/.zshrc /app/.oh-my-zsh

#USER app
#CMD ["/bin/zsh"]

### /James Developer ---------------------------------------------------------
### Management ---------------------------------------------------------------

# This is the ending of the fullstack image, but we add it again here
# to make it clear that this image is managed using supervisor

USER root

# Command that will run when the server starts
CMD ["/usr/bin/supervisord", "--nodaemon", "--configuration", "/etc/supervisord.conf"]

### /Management --------------------------------------------------------------
