FROM        jamesnesbitt/wunder-base
MAINTAINER  james.nesbitt@wunderkraut.com

### PHP-FPM -------------------------------------------------------------------

# Prepare to use the remi repo for PHP: our .repo file has certain repos enabled=1
ADD root/remi-release-7.rpm /root/remi-release-7.rpm
RUN /usr/bin/rpm -Uvh /root/remi-release-7.rpm
ADD etc/yum.repos.d/remi.repo /etc/yum.repos.d/remi.repo

# Install php-fpm
RUN /usr/bin/yum install --assumeyes --verbose \
    php-fpm php-opcache php-pecl-apc php-pdo php-mysql php-pgsql php-pecl-mongo \
    php-ldap php-sqlite php-pecl-memcache php-pecl-memcached php-gd php-mbstring \
    php-mcrypt php-xml php-pecl-yaml php-twig php-twig-ctwig php-pecl-uploadprogress

# Install blackfire
ADD etc/yum.repos.d/blackfire.repo /etc/yum.repos.d/blackfire.repo
RUN /usr/bin/yum install -y install pygpgme blackfire-php
RUN /usr/bin/sed -i "s,unix:///var/run/blackfire/agent.sock,tcp://blackfire.app:8707," /etc/php.d/zz-blackfire.ini

# make sure that the nginx user, created, has access to app user files
# nginx:x:999:999:Nginx web server:/var/lib/nginx:/sbin/nologin
RUN /usr/sbin/adduser --home-dir /var/lib/nginx --uid 999 --user-group --shell /bin/nologin --password "`openssl rand -base64 32 | openssl passwd -1 -stdin`" --comment "Nginx web server" nginx && \
    /usr/bin/gpasswd -a nginx app && \
    /usr/bin/mkdir -p /app/log/php-fpm && \
    /usr/bin/chown nginx:app /app/log/php-fpm

# Override some of the default php-fpm settings with our own.
ADD etc/php-fpm.d/www.conf /etc/php-fpm.d/www.conf
ADD etc/php.ini /etc/php.ini
ADD etc/php-fpm.conf /etc/php-fpm.conf

# Start the PHP-FPM service, and expose the 9000 port for TCP connections
ENTRYPOINT ["/usr/sbin/php-fpm"]
CMD ["-c /etc/php-fpm.conf"]
EXPOSE 9000

### /PHP-FPM ------------------------------------------------------------------
