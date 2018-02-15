# Demonstration of a Visiting-Badge-System

This is a Visiting Badge System done as a project during my classes at Lexicon Malmö and demonstrates setting up a Ubuntu Linux Desktop (server) and installing necessary software to run it.

## Ubuntu 16.04.3 LTS Installation

During this exercise a VirtualBox installation of Ubuntu 16.04 is used as Guest and functions as a server for the MySQL database and Apache (LAMP).

The user for the installation in Virtualbox was set to "user" and password "password".

You need to install one packet manually. In a terminal run
```
sudo apt-get install openssh-server 
```
In Vitualbox host's machinne connect to the guest using SSH and run
```
sudo apt-get install apache2 mysql-server php libapache2-mod-php php-mcrypt php-mysql php-gd
```

Password for MySQL server was set to "password" and is used throughout this exercise. "mysql-workbench" was also installed to ease the development.

(You can also install Virtualbox Guest Additions to use Copy-and-Paste. Choise is yours.)

Permission on the server file path:

In a terminal write
```
sudo chown -R user:www-data /var/www/html/
sudo mkdir -p /var/www/html/uploads
sudo chmod u=+rwxs,g=+rwsx,o=+srx /var/www/html/uploads
```

In MySQL Workbench execute
```
create database visitingbadgesystem;
use visitingbadgesystem;
create table visitors(id INTEGER PRIMARY KEY AUTO_INCREMENT, fullname VARCHAR(50) NOT NULL, phone VARCHAR(25) NOT NULL, errand VARCHAR(100), fromdate DATE NOT NULL, todate DATE NOT NULL, photothumb varchar(100) not null, photobadge varchar(100) not null,  active BOOLEAN not null );
create user 'user'@'localhost' IDENTIFIED BY 'password';
grant all privileges on visitingbadgesystem.visitors to 'user'@'localhost' identified by 'password';
```

Copy the files in the www folder to /var/www/html. Directory structure will look like:
```
$ find /var/www/html | sort
/var/www/html
/var/www/html/alle.jpg
/var/www/html/changestatus.php
/var/www/html/debug.php
/var/www/html/imageconvert.php
/var/www/html/imageresize.php
/var/www/html/index.php
/var/www/html/jquery
[...]
/var/www/html/jquery-ui
[...]
/var/www/html/register.php
/var/www/html/settings.php
/var/www/html/uploads
```

Image credit: Wikipedia (https://commons.wikimedia.org/wiki/File:%C3%85keshovs_all%C3%A9.jpg)
