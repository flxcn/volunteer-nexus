<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

define('HOST', 'localhost');
define('USERNAME', 'root');
define('PASSWORD', 'root');
define('DB_NAME', 'volunteer_nexus');

/*only for MAMP server (testing)*/
define('PORT', 8889);

$link = mysqli_connect(HOST, USERNAME, PASSWORD, DB_NAME, PORT);

if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

/*only for testing; reports all errors, disable in release*/
ini_set('display_errors',1); error_reporting(E_ALL);


/*for bluehost*/
/*
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
