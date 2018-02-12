<?php

/* 
 * File contains global variables like database credentials and global flags, i.e. debug
 */

?>

<?php

//All database specific settings and helpers

$debug = 1;

$servername = 'localhost:3306';
$username = 'user';
$password = 'password';
$database = 'visitingbadgesystem';

$uploadPathStore = '/var/www/html/uploads/';

$sqlqueryall = 'select * from visitors';
$sqlqueryallcurrent = 'select * from visitors where DATE(NOW()) BETWEEN fromdate AND todate';
