<?php
require_once 'config.php';
require_once 'mailer.php';

// search through the database to find all rows with the "reminderSent" row set as false;
// from these, futher filter to the only ones with the opportunity start date being tomorrow;
// send a message, using the sendMessage() function

?>
