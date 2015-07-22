FROM        jamesnesbitt/wunder-base
MAINTAINER  james.nesbitt@wunderkraut.com

### ElasticSearch -------------------------------------------------------------------

# Install the latest JDK
# (elasticsearch recommends using ORACLE java ... because they are controlled by Oracle)
RUN /usr/bin/yum --assumeyes --verbose install java-1.8.0-openjdk

# Install ElasticSearch GPG Key
RUN /usr/bin/rpm --import https://packages.elastic.co/GPG-KEY-elasticsearch

ADD etc/yum.repos.d/elasticsearch.repo etc/yum.repos.d/elasticsearch.repo
RUN /usr/bin/yum --assumeyes --verbose install elasticsearch

# Add the conf files

# Start the Redis service, and expose the 6379 port for TCP connections
CMD ["/usr/share/elasticsearch/bin/elasticsearch"]
EXPOSE 9300 # REST API Port
EXPOSE 9200 # Java API Port

### /ElasticSearch ------------------------------------------------------------------
