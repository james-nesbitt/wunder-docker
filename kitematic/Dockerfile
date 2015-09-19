FROM       jamesnesbitt/wunder-singleton
MAINTAINER james.nesbitt@wunderkraut.com

# Volume for the web-root
VOLUME /app/www

# Volume for application assets
VOLUME /app/assets
# Volume for backup materials
VOLUME /app/backups

# optional volume mounts for application functionality
#VOLUME /app/.platformsh
#VOLUME /app/.drush
#VOLUME /app/.ssh

ENV ELASTICSEARCH_CLUSTER project
ENV ELASTICSEARCH_NAME first

ENV DNSDOCK_ALIAS project.docker

ENV BLACKFIRE_SERVER_ID %BLACKFIRE_SERVER_ID
ENV BLACKFIRE_SERVER_TOKEN %BLACKFIRE_SERVER_TOKEN

ENV PLATFORMSH_CLI_API_TOKEN %TOKEN
#ENV PLATFORMSH_CLI_DEBUG 1
#ENV PLATFORMSH_CLI_DISABLE_CACHE 0
#ENV PLATFORMSH_CLI_SESSION_ID default

EXPOSE 80
EXPOSE 443
EXPOSE 3306
#EXPOSE 22

WORKDIR /app
