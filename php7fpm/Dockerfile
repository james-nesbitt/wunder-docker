FROM        jamesnesbitt/wunder-base
MAINTAINER  james.nesbitt@wunderkraut.com

### PHP-FPM -------------------------------------------------------------------

# Install the REMI repo : HOW NICE WOULD IT BE TO HAVE AN HTTPS ?!?
RUN /usr/bin/rpm -Uvh http://rpms.remirepo.net/enterprise/remi-release-7.rpm
# Prepare to use the remi repo for PHP: our .repo file has certain repos enabled=1
# Overwrite the default remi repos, to enable the remi and remi7 by default
ADD etc/yum.repos.d/remi.repo /etc/yum.repos.d/remi.repo
ADD etc/yum.repos.d/remi-php70.repo /etc/yum.repos.d/remi-php70.repo

# Install php-fpm - currently php7-rc6
RUN /usr/bin/yum install --assumeyes --verbose \
    php-fpm php-opcache php-pdo php-mysql php-pgsql \
    php-ldap php-gd php-mbstring php-mcrypt php-xml \
    php-pecl-apcu php-pecl-yaml php-pecl-uploadprogress php-pecl-zip
# Start the PHP-FPM service
RUN /usr/bin/systemctl enable php-fpm.service    

# Override some of the default php-fpm settings with our own.
ADD etc/php-fpm.conf /etc/php-fpm.conf
ADD etc/php-fpm.d/www.conf /etc/php-fpm.d/www.conf

# make sure that the nginx user, created, has access to app user files
# nginx:x:499:499:Nginx web server:/var/lib/nginx:/sbin/nologin
RUN /usr/sbin/adduser --home-dir /var/lib/nginx --uid 499 --user-group --shell /bin/nologin --password "`openssl rand -base64 32 | openssl passwd -1 -stdin`" --comment "Nginx web server" nginx && \
   /usr/sbin/usermod -a -G app,ftp,games nginx
RUN /usr/bin/mkdir -p /app/log/php-fpm && \
    /usr/bin/chown nginx:app /app/log/php-fpm

# expose the 9000 port for TCP connections
EXPOSE 9000

### /PHP-FPM ------------------------------------------------------------------
