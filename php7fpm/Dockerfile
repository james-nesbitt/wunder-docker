FROM        jamesnesbitt/wunder-base
MAINTAINER  james.nesbitt@wunderkraut.com

### PHP-FPM -------------------------------------------------------------------

ADD /root/remi-release-7.rpm /root/remi-release-7.rpm
RUN /usr/bin/rpm  -Uvh /root/remi-release-7.rpm

# Install php-fpm (libX11 and libX11-common come with php-gd)
RUN /usr/bin/yum --enablerepo=remi,remi-test install --assumeyes --verbose php70-php-fpm php70-php-common \
    php70-php-pecl-apc php70--php-pdo php70-mysql php70-php-pgsql php70-php-pecl-mongo \
    php70-php-ldap php70-php-sqlite php70-php-pecl-memcache php70-php-pecl-memcached php70-php-gd php70-php-mbstring \
    php70-php-mcrypt php70-php-xml php-pecl-yaml php70-php-pecl-selinux
# Override some of the default php-fpm settings with our own.
ADD etc/php.ini /etc/opt/remi/php70/php.ini
ADD etc/php-fpm.conf /etc/opt/remi/php70/php-fpm.conf
ADD etc/php-fpm.d/www.conf /etc/opt/remi/php70/php-fpm.d/www.conf

# make sure that the nginx user, created, has access to app user files
# nginx:x:999:999:Nginx web server:/var/lib/nginx:/sbin/nologin
RUN /usr/sbin/adduser --home-dir /var/lib/nginx --uid 999 --user-group --shell /bin/nologin --password "`openssl rand -base64 32 | openssl passwd -1 -stdin`" --comment "Nginx web server" nginx && \
    /usr/bin/gpasswd -a nginx app
RUN /usr/bin/mkdir -p /app/log/php-fpm && \
    /usr/bin/chown nginx:app /app/log/php-fpm

# Start the PHP-FPM service, and expose the 9000 port for TCP connections
CMD ["/opt/remi/php70/root/sbin/php-fpm",  "-c /opt/remi/php70/php-fpm.conf"]
EXPOSE 9000

### /PHP-FPM ------------------------------------------------------------------
