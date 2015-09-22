FROM       jamesnesbitt/wunder-lampstackplusdev
MAINTAINER james.nesbitt@wunderkraut.com

# Volume for the web-root
VOLUME /app/www

# Environment variables
ENV BLACKFIRE_SERVER_ID %BLACKFIRE_SERVER_ID
ENV BLACKFIRE_SERVER_TOKEN %BLACKFIRE_SERVER_TOKEN

ENV PLATFORMSH_CLI_API_TOKEN %TOKEN

# Expose some ports
EXPOSE 80
EXPOSE 443
EXPOSE 3306

# Set the workdir
WORKDIR /app
