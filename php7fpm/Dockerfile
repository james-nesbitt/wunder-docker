FROM        jamesnesbitt/wunder-base
MAINTAINER  james.nesbitt@wunderkraut.com

### PHP-FPM -------------------------------------------------------------------

# Prepare to use the remi repo for PHP
ADD root/remi-release-7.rpm /root/remi-release-7.rpm
RUN /usr/bin/rpm -Uvh /root/remi-release-7.rpm
ADD etc/yum.repos.d/remi-php70.repo /etc/yum.repos.d/remi-php70.repo

# Install php-fpm : php-pecl-mongo php-sqlite php-pecl-apc php-pecl-memcache php-suhosin php-pecl-yaml php-xml
RUN /usr/bin/yum install --assumeyes --verbose \
    php-fpm php-common php-pdo php-mysql php-pgsql \
    php-ldap php-pecl-memcached php-gd php-mbstring php-mcrypt

# Override some of the default php-fpm settings with our own.
ADD etc/php-fpm.conf /etc/php-fpm.conf
ADD etc/php-fpm.d/www.conf /etc/php-fpm.d/www.conf

# make sure that the nginx user, created, has access to app user files
# nginx:x:999:999:Nginx web server:/var/lib/nginx:/sbin/nologin
RUN /usr/sbin/adduser --home-dir /var/lib/nginx --uid 999 --user-group --shell /bin/nologin --password "`openssl rand -base64 32 | openssl passwd -1 -stdin`" --comment "Nginx web server" nginx && \
    /usr/bin/gpasswd -a nginx app
RUN /usr/bin/mkdir -p /app/log/php-fpm && \
    /usr/bin/chown nginx:app /app/log/php-fpm

# Start the PHP-FPM service, and expose the 9000 port for TCP connections
ENTRYPOINT ["/usr/sbin/php-fpm"]
CMD ["-c /etc/php-fpm.conf"]
EXPOSE 9000

### /PHP-FPM ------------------------------------------------------------------
