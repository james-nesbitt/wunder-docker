FROM        jamesnesbitt/wunder-base
MAINTAINER  james.nesbitt@wunderkraut.com

### PHP-FPM -------------------------------------------------------------------

# Add the zend php repo
ADD etc/yum.repos.d/zend-php7.repo /etc/yum.repos.d/zend-php7.repo

# Install php-fpm
RUN /usr/bin/yum --assumeyes --verbose install php7-nightly

# Override some of the default php-fpm settings with our own.
ADD etc/php-fpm.conf /usr/local/php7/etc/php-fpm.conf
ADD etc/php-fpm.d/www.conf /usr/local/php7/etc/php-fpm.d/www.conf

# make sure that the nginx user, created, has access to app user files
# nginx:x:999:999:Nginx web server:/var/lib/nginx:/sbin/nologin
RUN /usr/sbin/adduser --home-dir /var/lib/nginx --uid 999 --user-group --shell /bin/nologin --password "`openssl rand -base64 32 | openssl passwd -1 -stdin`" --comment "Nginx web server" nginx && \
    /usr/bin/gpasswd -a nginx app
RUN /usr/bin/mkdir -p /app/log/php-fpm && \
    /usr/bin/chown nginx:app /app/log/php-fpm

# Start the PHP-FPM service, and expose the 9000 port for TCP connections
ENTRYPOINT ["/usr/local/php7/sbin/php-fpm"]
CMD ["-c /usr/local/php7/etc/php-fpm.conf"]
EXPOSE 9000

### /PHP-FPM ------------------------------------------------------------------
