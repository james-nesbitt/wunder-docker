FROM       jamesnesbitt/wunder-lampstackplusdev
MAINTAINER james.nesbitt@wunderkraut.com

RUN /usr/sbin/usermod -a -G ftp nginx
RUN /usr/sbin/usermod -a -G games nginx

# Volume for the web-root
VOLUME /app/www

# Expose some ports
EXPOSE 80
EXPOSE 443
EXPOSE 3306

# Set the workdir
WORKDIR /app
