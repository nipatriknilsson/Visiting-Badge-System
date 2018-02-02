DRAFT

# Visiting-Badge-System

This is a Visiting Badge System done as a project during my classes at Lexicon Malmö and demonstrates setting up a Ubuntu Linux Desktop (server) and installing necessary software to run it.

## Ubuntu 16.04.3 LTS Installation

During this exercise a VirtualBox installation of Ubuntu 16.04 is used as Guest You need to install one packet manually. In a terminal run
```
sudo apt-get install openssh-server 
```
In Vitualbox host's machinne connect to the guest using SSH and run
```
sudo apt-get install apache2 mysql-server php libapache2-mod-php php-mcrypt php-mysql php-gd
```

Password for MySQL server was set to "password" and is used throughout this exercise. "mysql-workbench" was also installed to ease the development.


Permission on the server file path:

In a terminal write
```
sudo chown -R user:www-data /var/www/html/
sudo chmod u=+rwxs,g=+rwsx,o=+srx /var/www/html/uploads
```

In MySQL Workbench write
```
create database visitingbadgesystem;
use visitingbadgesystem;
create table visitorsvisitors(id INTEGER PRIMARY KEY AUTO_INCREMENT, fullname VARCHAR(50) NOT NULL, phone VARCHAR(25) NOT NULL, errand VARCHAR(100), fromdate DATE NOT NULL, todate DATE NOT NULL, photo varchar(100) not null, loggedin BOOLEAN  );
create user user IDENTIFIED BY 'password';
```

