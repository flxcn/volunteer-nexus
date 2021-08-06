<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
    header("location: sign-in.php");
    exit;
}

require '../classes/EngagementDeletion.php';
$obj = new EngagementDeletion();

if($_SERVER["REQUEST_METHOD"] == "GET")
{
    // Check input errors before inserting in database
    if(isset($_GET["engagement_id"]))
    {
        // Set engagement_id
        $engagement_id = $_GET["engagement_id"];
        $obj->setEngagementId($engagement_id);

        // Set engagement_status
        $sponsor_id = $_SESSION["sponsor_id"];
        $obj->setSponsorId($sponsor_id);

        if($obj->deleteEngagement()) {
            echo "<b>Deleted!</b>";
        }
    }
}
?>
                