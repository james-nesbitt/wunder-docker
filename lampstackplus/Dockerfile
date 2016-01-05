FROM       jamesnesbitt/wunder-base
MAINTAINER james.nesbitt@wunderkraut.com

### MariaDB ------------------------------------------------------------------

# Install MariaDB
ADD etc/yum.repos.d/mariadb.repo etc/yum.repos.d/mariadb.repo
RUN /usr/bin/yum --assumeyes --verbose install mariadb-server mariadb
RUN /usr/bin/mkdir -p /app/log/mysql

# Overwrite installed mariadb configuration with a custom one
ADD etc/my.cnf.d/server.cnf /etc/my.cnf.d/server.cnf
ADD etc/my.cnf /etc/my.cnf

# Create our MySQL log folder : we put logs here so that we can easily share a volume across all containers
RUN /usr/bin/mkdir -p /app/log/mariadb && \
    /usr/bin/mkdir -p /var/run/mariadb && \
    /usr/bin/chown mysql:mysql /app/log/mariadb /var/run/mariadb

# Initialize the db
RUN /usr/bin/mysql_install_db --basedir=/usr --datadir=/var/lib/mysql --user=mysql --log-error=/app/log/mariadb/error.log --pid-file=/var/run/mariadb/mariadb.pid --socket=/var/run/mariadb/mariadb.sock --bind=0.0.0.0 --port=3306

# Initial db setup
# - Secure the db, leaving a lame root password, that can be changed in sub images, or in containers
#      (now leaving the root password as blank)
# - Kill the anonymous users
# - Kill off the demo database
# - Add our custom app DB
# - Grant needed privileges on our needed app DB
# - Make our changes take effect
RUN (/usr/bin/mysqld_safe &) && sleep 5 && \
   mysql -uroot -e "DELETE FROM mysql.user WHERE User=''" && \
   mysql -uroot -e "DROP DATABASE test" && \
   mysql -uroot -e "CREATE DATABASE app" && \
   mysql -uroot -e "GRANT ALL on app.* to app IDENTIFIED BY \"app\"" && \
   mysql -uroot -e "FLUSH PRIVILEGES"

# REMOVE ROOT PASSWORD: mysql -uroot -e "UPDATE mysql.user SET Password=PASSWORD('RESETME') WHERE User='root'"

# AS a command, run the mariadb server, and expose the 3306 port for TCP connections
#ENTRYPOINT ["/usr/bin/mysqld_safe"]
#CMD ["--basedir=/usr", "--datadir=/var/lib/mysql", "--user=mysql", "--log-error=/app/log/mariadb/error.log", "--pid-file=/var/run/mariadb/mariadb.pid, "--bind=0.0.0.0", "--port=3306"]

EXPOSE 3306

### /MariaDB -----------------------------------------------------------------
### NGINX --------------------------------------------------------------------

# Install the nginx signing key
ADD root/nginx_signing.key /root/nginx_signing.key
RUN /usr/bin/rpm --verbose --import http://nginx.org/keys/nginx_signing.key

# Install nginx (make it run as the nginx user)
ADD etc/yum.repos.d/nginx.repo /etc/yum.repos.d/nginx.repo
RUN /usr/bin/yum install --assumeyes --verbose nginx

ADD etc/nginx/nginx.conf /etc/nginx/nginx.conf
ADD etc/nginx/conf.d /etc/nginx/conf.d

# give ownership of logs to the nginx user
RUN /usr/bin/mkdir -p /app/log/nginx && \
    /usr/bin/chown -R nginx:nginx /app/log/nginx && \
    /usr/bin/chmod -R ug+rwX /app/log/nginx

# make sure that the nginx user, created, has access to app user files
# (ftp, user groups are required for typical cases like OSX, Ubuntu)
RUN /sbin/usermod -a -G users,app,ftp,games nginx

# As a command run the nginx server in foreground mode.
# IF nginx.conf does not have it, add this: "-g 'daemon off'"
#ENTRYPOINT ["/usr/sbin/nginx"]
#CMD ["-c/etc/nginx/nginx.conf"]
EXPOSE 80 443

### /NGINX -------------------------------------------------------------------
### PHP-FPM ------------------------------------------------------------------

# Prepare to use the remi repo for PHP: our .repo file has certain repos enabled=1
ADD root/remi-release-7.rpm /root/remi-release-7.rpm
RUN /usr/bin/rpm -Uvh /root/remi-release-7.rpm
ADD etc/yum.repos.d/remi.repo /etc/yum.repos.d/remi.repo

# Install php-fpm
RUN /usr/bin/yum install --assumeyes --verbose \
    php-fpm php-opcache php-pecl-apcu php-pdo php-mysql php-pgsql php-pecl-mongo \
    php-ldap php-sqlite php-pecl-memcache php-pecl-memcached php-gd php-mbstring \
    php-mcrypt php-xml php-pecl-yaml php-twig php-twig-ctwig php-pecl-uploadprogress \
    php-pecl-xdebug php-pecl-zip

# make sure that the nginx user, created, has access to app user files
# nginx:x:499:499:Nginx web server:/var/lib/nginx:/sbin/nologin
RUN /usr/bin/mkdir -p /app/log/php-fpm && \
    /usr/bin/chown nginx:app /app/log/php-fpm

# Override some of the default php-fpm settings with our own.
ADD etc/php.ini /etc/php.ini
ADD etc/php-fpm.conf /etc/php-fpm.conf
ADD etc/php-fpm.d/www.conf /etc/php-fpm.d/www.conf

# Add some php extension configuraiton overrides
ADD etc/php.d/01-defaultdatetime.ini /etc/php.d/01-defaultdatetime.ini
ADD etc/php.d/40-apcu.ini /etc/php.d/40-apcu.ini

# Start the PHP-FPM service, and expose the 9000 port for TCP connections
#ENTRYPOINT ["/usr/sbin/php-fpm"]
#CMD ["-c /etc/php-fpm.conf"]
#EXPOSE 9000

### /PHP-FPM -----------------------------------------------------------------
### Blackfire ----------------------------------------------------------------

# Install blackfire
ADD etc/yum.repos.d/blackfire.repo /etc/yum.repos.d/blackfire.repo
RUN /usr/bin/yum install -y install pygpgme blackfire-agent blackfire-php
RUN /bin/mkdir -p /var/run/blackfire && \
    /bin/mkdir -p /var/log/blackfire

# Enable the blackfire php agent php extension
ADD etc/php.d/zz-blackfire.ini /etc/php.d/zz-blackfire.ini

# These ENV vars can be used to configure the server
ENV BLACKFIRE_SERVER_ID %BLACKFIRE_SERVER_ID
ENV BLACKFIRE_SERVER_TOKEN %BLACKFIRE_SERVER_TOKEN

#ENTRYPOINT ["blackfire-agent"]
#CMD ["--config", "/dev/null"]
#EXPOSE 8707

### /Blackfire --------------------------------------------------------------
### REDIS -------------------------------------------------------------------

# Install Redis
RUN /usr/bin/yum --assumeyes --verbose install redis
RUN /bin/mkdir -p /app/log/redis

# Add the conf files
ADD etc/redis.conf /etc/redis.conf

# Start the Redis service, and expose the 6379 port for TCP connections
#ENTRYPOINT ["/usr/bin/redis-server"]
#CMD ["/etc/redis.conf"]
#EXPOSE 6379

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

# Start the Easticsearch service, and expose the REST and API ports for TCP connections
#CMD ["/usr/share/elasticsearch/bin/elasticsearch"]
# REST API Port
#EXPOSE 9300
# Java API Port
#EXPOSE 9200

### /ElasticSearch ------------------------------------------------------------
### Management ---------------------------------------------------------------

USER root

# Install Supervisor
RUN /usr/bin/yum install -y supervisor
ADD etc/supervisord.d /etc/supervisord.d
#EXPOSE 9001

# Command that will run when the server starts
USER root
CMD ["/usr/bin/supervisord", "--nodaemon", "--configuration", "/etc/supervisord.conf"]

### /Management --------------------------------------------------------------
