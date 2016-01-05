FROM        jamesnesbitt/wunder-base
MAINTAINER  james.nesbitt@wunderkraut.com

### HHVM ---------------------------------------------------------------------

# Add the no1youknows HHVM repo (unverified)
ADD etc/yum.repos.d/no1youknowz-hhvm-repo-epel-7.repo /etc/yum.repos.d/no1youknowz-hhvm-repo-epel-7.repo

# Add the HHVM package
RUN /usr/bin/yum --assumeyes --verbose install hhvm

# make sure that the nginx user, created, has access to app user files
# nginx:x:499:499:Nginx web server:/var/lib/nginx:/sbin/nologin
RUN /usr/sbin/adduser --home-dir /var/lib/nginx --uid 499 --user-group --shell /bin/nologin --password "`openssl rand -base64 32 | openssl passwd -1 -stdin`" --comment "Nginx web server" nginx && \
    /usr/bin/gpasswd -a nginx app && \
    /usr/bin/mkdir -p /var/run/hhvm && \
    /usr/bin/chown nginx:app /var/run/hhvm && \
    /usr/bin/mkdir -p /app/log/hhvm && \
    /usr/bin/chown nginx:app /app/log/hhvm

ADD etc/hhvm /etc/hhvm

ENTRYPOINT ["/usr/bin/hhvm"]
CMD ["--mode=server", "--config=/etc/hhvm/server.ini", "--user=nginx"]
# ExecStart=/usr/local/bin/hhvm --config /etc/hhvm/server.ini --user nobody

### /HHVM --------------------------------------------------------------------
