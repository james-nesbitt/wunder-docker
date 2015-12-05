FROM        jamesnesbitt/wunder-base
MAINTAINER  james.nesbitt@wunderkraut.com

### ElasticSearch -------------------------------------------------------------------

# Install the latest JDK
# (elasticsearch recommends using ORACLE java ... because they are Oracle)
RUN /usr/bin/yum --assumeyes --verbose install java-1.8.0-openjdk

# Install ElasticSearch GPG Key
RUN /usr/bin/rpm --import https://packages.elastic.co/GPG-KEY-elasticsearch

# Add the ES repo and install ES
ADD etc/yum.repos.d/elasticsearch.repo etc/yum.repos.d/elasticsearch.repo
RUN /usr/bin/yum --assumeyes --verbose install elasticsearch

# Set some vars used by the elasticsearch.yml
ENV ELASTICSEARCH_CLUSTER project
ENV ELASTICSEARCH_NAME first

# Expose the two ports that elastic search uses
# REST API Port
# Java API Port
EXPOSE 9300
EXPOSE 9200

# Start the Redis service, and expose the 6379 port for TCP connections
ENTRYPOINT ["/usr/share/elasticsearch/bin/elasticsearch"]

### /ElasticSearch ------------------------------------------------------------------
