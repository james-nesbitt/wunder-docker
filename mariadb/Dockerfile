FROM       jamesnesbitt/wunder-base
MAINTAINER james.nesbitt@wunderkraut.com

### MariaDB --------------------------------------------------------------

# Install MariaDB
ADD etc/yum.repos.d/mariadb.repo etc/yum.repos.d/mariadb.repo
RUN /usr/bin/yum --assumeyes --verbose install mariadb-server mariadb

# Overwrite installed mariadb configuration with a custom one
ADD etc/my.cnf.d/server.cnf /etc/my.cnf.d/server.cnf
ADD etc/my.cnf /etc/my.cnf

# Create our MySQL log folder : we put logs here so that we can easily share a volume across all containers
RUN /usr/bin/mkdir -p /app/log/mariadb && \
    /usr/bin/chown mysql:mysql /app/log/mariadb

# Initialize the db
RUN /usr/bin/mysql_install_db --basedir=/usr --datadir=/var/lib/mysql --user=mysql --log-error=/app/log/mariadb/error.log --pid-file=/var/run/mariadb/mariadb.pid --socket=/var/run/mariadb/mariadb.sock --bind=0.0.0.0 --port=3306

# Initial db setup
# - Secure the db, leaving a lame root password, that can be changed in sub images, or in containers
# - Make sure that NOBODY can access the server without a password
# - Kill the anonymous users
# - Kill off the demo database
# - Make our changes take effect
#RUN (/usr/bin/mysqld_safe &) && sleep 3 && \
#    mysql -uroot -e "UPDATE mysql.user SET Password=PASSWORD('RESETME') WHERE User='root'" && \
#    mysql -uroot -e "DELETE FROM mysql.user WHERE User=''" && \
#    mysql -uroot -e "DROP DATABASE test" && \
#    mysql -uroot -e "FLUSH PRIVILEGES"

# AS a command, run the mariadb server, and expose the 3306 port for TCP connections

ENTRYPOINT ["/usr/bin/mysqld_safe"]
CMD ["--basedir=/usr", "--datadir=/var/lib/mysql", "--user=mysql", "--log-error=/app/log/mariadb/error.log", "--pid-file=/var/run/mariadb/mariadb.pid", "--socket=/var/run/mariadb/mariadb.sock", "--bind=0.0.0.0", "--port=3306"]
EXPOSE 3306

### /MariaDB --------------------------------------------------------------------
