FROM centos/systemd

# update package

RUN yum -y update

# install lampp

RUN yum -y install http://rpms.remirepo.net/enterprise/remi-release-7.rpm
RUN yum -y install epel-release yum-utils
RUN yum-config-manager --disable remi-php54
RUN yum-config-manager --enable remi-php73
RUN yum -y install php php-cli php-fpm php-mysqlnd php-zip php-devel php-gd php-mcrypt php-mbstring php-curl php-xml php-pear php-bcmath php-json && \
	yum install -y httpd && yum install -y phpmyadmin && yum install -y mod_ssl


# Install composer
WORKDIR /tmp
RUN curl -sS https://getcomposer.org/installer | php
RUN mv /tmp/composer.phar /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer

# Configure php.ini
RUN sed -i -e "s/short_open_tag = Off/short_open_tag = On/" /etc/php.ini


# mysql_install
# COPY phpMyAdmin.conf /etc/httpd/conf.d/phpMyAdmin.conf

# config httpd.conf
# RUN yum install -y mariadb-server mariadb
COPY httpd.conf /etc/httpd/conf/httpd.conf
COPY ssl.conf /etc/httpd/conf.d/ssl.conf

COPY ./edu /var/www/html


# Add user for laravel application
# RUN groupadd -g 1000 www
# RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
# COPY . /var/www

# Copy existing application directory permissions
# COPY --chown=www:www . /var/www
# COPY ./reports /var/www/html
# COPY --chown=www:www ./reports /var/www/html
# Change current user to www
# USER www

# ENV APACHE_LOG_DIR /var/log/apache2
WORKDIR /var/www/html

RUN chmod -R 777 /var/www/html/storage
CMD composer install 
CMD composer dump-autoload
COPY .env /var/www/html/.env
CMD php artisan cache:clear
CMD apachectl -D FOREGROUND

# EXPOSE 80 443
