FROM        jamesnesbitt/wunder-base
MAINTAINER  james.nesbitt@wunderkraut.com

### Blackfire ----------------------------------------------------------------

# Install blackfire
ADD etc/yum.repos.d/blackfire.repo /etc/yum.repos.d/blackfire.repo
RUN /usr/bin/yum install -y install pygpgme blackfire-agent blackfire-php
RUN /bin/mkdir -p /var/run/blackfire && \
    /bin/mkdir -p /var/log/blackfire

ENTRYPOINT ["blackfire-agent"]
CMD ["--socket", "tcp://0.0.0.0:8707", "--config", "/dev/null"]

### /Blackfire ---------------------------------------------------------------
