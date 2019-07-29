<?php

define('HOST', 'localhost');
define('USERNAME', 'root');
define('PASSWORD', 'root');
define('DB_NAME', 'volunteerconnect');

/*only for MAMP server (testing)*/
define('PORT', 8889);

$link = mysqli_connect(HOST, USERNAME, PASSWORD, DB_NAME, PORT);

if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

ini_set('display_errors',1); error_reporting(E_ALL);

/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password)
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'westlal0_vendor');
define('DB_PASSWORD', 'IBMmodelf');
define('DB_NAME', 'westlal0_volunteerconnect');

/* Attempt to connect to MySQL database
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}*/

?>
