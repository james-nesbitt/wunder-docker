FROM       jamesnesbitt/wunder-base
MAINTAINER james.nesbitt@wunderkraut.com

### Management ---------------------------------------------------------------

### /Management --------------------------------------------------------------

# Install Supervisor
RUN /usr/bin/yum install -y supervisor
ADD etc/supervisord.d /etc/supervisord.d
EXPOSE 9001

# Command that will run when the server starts
USER root
CMD ["/usr/bin/supervisord", "--nodaemon", "--configuration /etc/supervisord.conf"]

### MariaDB ------------------------------------------------------------------

# Install MariaDB
ADD etc/yum.repos.d/mariadb.repo etc/yum.repos.d/mariadb.repo
RUN /usr/bin/yum --assumeyes --verbose install mariadb-server mariadb

# Overwrite installed mariadb configuration with a custom one
ADD etc/my.cnf.d/server.cnf /etc/my.cnf.d/server.cnf
ADD etc/my.cnf /etc/my.cnf

# Create our MySQL log folder : we put logs here so that we can easily share a volume across all containers
RUN /usr/bin/mkdir -p /app/log/mariadb && \
    /usr/bin/chown mysql:mysql /app/log/mariadb

# Initialize the db
RUN /usr/bin/mysql_install_db --basedir=/usr --datadir=/var/lib/mysql --user=mysql --log-error=/app/log/mariadb/error.log --pid-file=/var/run/mariadb/mariadb.pid --socket=/var/run/mariadb/mariadb.sock --bind=0.0.0.0 --port=3306

# Initial db setup
# - Secure the db, leaving a lame root password, that can be changed in sub images, or in containers
# - Kill the anonymous users
# - Kill off the demo database
# - Add our custom project DB
# - Grant needed privileges on our needed project DB
# - Make our changes take effect
RUN (/usr/bin/mysqld_safe &) && sleep 3 && \
   mysql -uroot -e "UPDATE mysql.user SET Password=PASSWORD('RESETME') WHERE User='root'" && \
   mysql -uroot -e "DELETE FROM mysql.user WHERE User=''" && \
   mysql -uroot -e "DROP DATABASE test" && \
   mysql -uroot -e "CREATE DATABASE project" && \
   mysql -uroot -e "GRANT ALL on project.* to project@* IDENTIFIED BY \"project\"" && \
   mysql -uroot -e "FLUSH PRIVILEGES" && \

# AS a command, run the mariadb server, and expose the 3306 port for TCP connections
#ENTRYPOINT ["/usr/bin/mysqld_safe"]
#CMD ["--basedir=/usr", "--datadir=/var/lib/mysql", "--user=mysql", "--log-error=/app/log/mariadb/error.log", "--pid-file=/var/run/mariadb/mariadb.pid", "--socket=/var/run/mariadb/mariadb.sock", "--bind=0.0.0.0", "--port=3306"]
EXPOSE 3306

### /MariaDB -----------------------------------------------------------------
### NGINX --------------------------------------------------------------------

# Install nginx (make it run as the nginx user)
RUN /usr/bin/yum install --assumeyes --verbose nginx

ADD etc/nginx/nginx.conf /etc/nginx/nginx.conf
ADD etc/nginx/conf.d /etc/nginx/conf.d

# give ownership of logs to the nginx user
RUN /usr/bin/mkdir -p /app/log/nginx && \
    /usr/bin/chown -R nginx:nginx /app/log/nginx && \
    /usr/bin/chmod -R ug+rwX /app/log/nginx

# make sure that the nginx user, created, has access to app user files
RUN /usr/bin/gpasswd -a nginx app

# As a command run the nginx server in foreground mode.
# IF nginx.conf does not have it, add this: "-g 'daemon off'"
#ENTRYPOINT ["/usr/sbin/nginx"]
#CMD ["-c/etc/nginx/nginx.conf"]
EXPOSE 80 443

### /NGINX -------------------------------------------------------------------
### PHP-FPM ------------------------------------------------------------------

# Install php-fpm (libX11 and libX11-common come with php-gd)
RUN /usr/bin/yum install --assumeyes --verbose php-fpm php-common \
    php-pecl-apc php-pdo php-mysql php-pgsql php-pecl-mongo \
    php-ldap php-sqlite php-pecl-memcache php-pecl-memcached php-gd php-mbstring \
    php-mcrypt php-xml php-pecl-yaml
# Override some of the default php-fpm settings with our own.
ADD etc/php-fpm.d/www.conf /etc/php-fpm.d/www.conf

# Start the PHP-FPM service, and expose the 9000 port for TCP connections
#ENTRYPOINT ["/usr/sbin/php-fpm"]
#CMD ["-c /etc/php-fpm.conf"]
EXPOSE 9000

### /PHP-FPM -----------------------------------------------------------------
### Blackfire ----------------------------------------------------------------

# Install blackfire
ADD etc/yum.repos.d/blackfire.repo /etc/yum.repos.d/blackfire.repo
RUN /usr/bin/yum install -y install pygpgme blackfire-agent blackfire-php
RUN /bin/mkdir -p /var/run/blackfire && \
    /bin/mkdir -p /var/log/blackfire

#ENTRYPOINT ["blackfire-agent"]
#CMD ["--socket", "tcp://0.0.0.0:8707", "--config", "/dev/null"]

### /Blackfire --------------------------------------------------------------
### REDIS -------------------------------------------------------------------

# Install Redis
RUN /usr/bin/yum --assumeyes --verbose install redis

# Add the conf files
ADD etc/redis.conf /etc/redis.conf

# Start the Redis service, and expose the 6379 port for TCP connections
#ENTRYPOINT ["/usr/bin/redis-server"]
#CMD ["/etc/redis.conf"]
EXPOSE 6379

### /REDIS ------------------------------------------------------------------
### ElasticSearch -----------------------------------------------------------

# Install the latest JDK
# (elasticsearch recommends using ORACLE java ... because they are Oracle)
RUN /usr/bin/yum --assumeyes --verbose install java-1.8.0-openjdk

# Install ElasticSearch GPG Key
RUN /usr/bin/rpm --import https://packages.elastic.co/GPG-KEY-elasticsearch

# Add the ES repo and install ES
ADD etc/yum.repos.d/elasticsearch.repo etc/yum.repos.d/elasticsearch.repo
RUN /usr/bin/yum --assumeyes --verbose install elasticsearch

# Set some vars used by the elasticsearch.yml
ENV ELASTICSEARCH_CLUSTER project
ENV ELASTICSEARCH_NAME first

# Start the Redis service, and expose the 6379 port for TCP connections
#CMD ["/usr/share/elasticsearch/bin/elasticsearch"]
#EXPOSE 9300 # REST API Port
EXPOSE 9200 # Java API Port

### /ElasticSearch ------------------------------------------------------------
### DEVELOPER  ---------------------------------------------------------------

# Install some tools that are usefull for a developer
RUN /usr/bin/yum install -y openssl tar git zsh sudo vim

# Set up some stuff for the app user
RUN /usr/bin/echo "app        ALL=(ALL)       NOPASSWD: ALL" > /etc/sudoers.d/app

# Install nodejs and npm, which gets used for lots of stuff with SASS/SCSS
RUN /usr/bin/yum install --assumeyes --verbose nodejs npm
RUN /usr/bin/npm install gulp -g

# Install composer which will be used to install drush
RUN /usr/bin/curl -sS https://getcomposer.org/installer | php -- --filename=composer --install-dir=/usr/local/bin

# Everything else is run as the platform user
USER app

# Install DRUSH, which is a requiremnt for platform
RUN composer global require drush/drush:dev-master
# RUN composer global require drush/config-extra

# Install platform.sh cli
RUN composer global require platformsh/cli:@stable
# RUN composer global require "commerceguys/platform-cli=1.*"

# Some usefull env variables
ENV TERM=xterm

#CMD ["/bin/bash"]

### /DEVELOPER ---------------------------------------------------------------
### James Developer ----------------------------------------------------------

# Make the app user use the zsh shell with oh-my-zsh installed
ADD .oh-my-zsh /app/.oh-my-zsh
ADD .zshrc /app/.zshrc

USER root

RUN /usr/sbin/usermod -s /bin/zsh app && \
    chown -R app:app /app/.zshrc /app/.oh-my-zsh

USER app

#CMD ["/bin/zsh"]

### /James Developer ---------------------------------------------------------
