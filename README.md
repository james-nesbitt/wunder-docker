# Wunder-Docker

https://github.com/james-nesbitt/wunder-docker

This is a set of service and command Docker container images, that can be used together to provide common web service, with a focus on Drupal.

The base image is used as a FROM for other Wunder images.  This image provides a standardization for user usage, and source code location, for our approach to build a containerized apps.

There are certain problems that we were running into when splitting an application across various containers:

- We didn't like some of the source builds (we prefer CentOS over Ubuntu);
- User/File permissions were difficult to align, both to give privilege, and to remove privilege;
- Uncertain location for things like source code and log files.
- Some files that you want access to outside of the box are hard to get access to


So the solution was:

* "Build a base box, with some standardized tools, users and a place to put code.  Other boxes can then extend this image, and gain from the standardized approach"

The following standardizations were used:

1. Standard paths:
    - /app is used as a root for all appication related files
    - /app is also a standard HOME for the default user
    - /app/logs should be used for service/application logs


2. Standard users were create:
    - app (UID:1000, GID:1000) a standard privilege user who owns source code.
    - core (UID:500, GID:500) similar to the CoreOS core user, a higher privilege user
    - Any service that needs read access to source code should be added to the "app" group
    - The app user should be added to any container group which may produce files that are needed (such as log files)


* Some images use Composer, and tend to put COMPOSER_HOME=/composer; they also tend to alter the PATH variable to include /composer/vendor/bin


Using this approach, it becomes easy to:

- bind files across images (and from a host) and maintain a common set of permissions
- share binds for things like common log folders
- keep separation between RO and RW code

And results in extra bonuses like:

- easy re-use of images without the need for project/app specific builds

## COMMON APPROACHES:

Using this base image, it becomes easy to implement separated applications, without needing to re-use binds, and with good privilege separation, and sharing of files.

- Consider creating a container that will hold source, perhaps in /app/src or /app/www : this then gives a bind source for other containers.  Such an approach is easily repeatable in production and local environments, where local environments can use a host bind, and production environments can build a container using something like a git clone.  Consider only ever binding this as Read-Only.

- Consider creating a container based on this image, that will contain volumes for non-architecture files such as assets, caches, renders or compiles.  This container can be volatile, or can be considered a central file repository for various application components.

(remember that it is easy to mount all container volumes to another container using --volumes-from my_container, in the Docker run)


## Images

- base : a base image
- mariadb : a MariaDB 10.x database server, from the mariadb provided repo
- nginx : a stable Centos nginx server, from the nginx provided repo

3 interchangeable FPM servers
- php6fpm : PHP 6 FPM service
- php7fpm : PHP & FPM service, from the zend repo
- hhvm : HHVM FPM service
