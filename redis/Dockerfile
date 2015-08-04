FROM        jamesnesbitt/wunder-base
MAINTAINER  james.nesbitt@wunderkraut.com

### REDIS -------------------------------------------------------------------

# Install Redis
RUN /usr/bin/yum --assumeyes --verbose install redis

# Make sure that we have a log folder
RUN /usr/bin/mkdir -p /app/log/redis/

# Add the conf files
ADD etc/redis.conf /etc/redis.conf

# Start the Redis service, and expose the 6379 port for TCP connections
ENTRYPOINT ["/usr/bin/redis-server"]
CMD ["/etc/redis.conf"]
EXPOSE 6379

### /REDIS ------------------------------------------------------------------
