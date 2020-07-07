<?php
//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require '../classes/SponsorEventReader.php';

$sponsor_id = $_SESSION["sponsor_id"];
$obj = new SponsorEventReader($sponsor_id);

if($_SERVER["REQUEST_METHOD"] == "GET")
{

    if(isset($_GET["interval"]))
    {
        // Set engagement_id
        $interval = $_GET["interval"];

        if(strcmp($interval, "upcoming") == 0) {
            $events = $obj->getUpcomingSponsoredEvents();
            $formatted_table = $obj->formatEventTable($events);
            if($formatted_table) {
              echo $formatted_table;
            }
            else {
              echo "Error!";
            }
        }

        elseif(strcmp($interval, "ongoing") == 0) {
            $events = $obj->getOngoingSponsoredEvents();
            $formatted_table = $obj->formatEventTable($events);
            if($formatted_table) {
              echo $formatted_table;
            }
            else {
              echo "Error!";
            }
        }

        elseif(strcmp($interval, "completed") == 0) {
            $events = $obj->getCompletedSponsoredEvents();
            $formatted_table = $obj->formatEventTable($events);
            if($formatted_table) {
              echo $formatted_table;
            }
            else {
              echo "Error!";
            }
        }

        elseif(strcmp($interval, "all") == 0) {
            $events = $obj->getSponsoredEvents();
            $formatted_table = $obj->formatEventTable($events);
            if($formatted_table) {
              echo $formatted_table;
            }
            else {
              echo "Error!";
            }
        }

        else {
            echo "Error. Interval parameter not valid.";
        }
    }
}
?>
