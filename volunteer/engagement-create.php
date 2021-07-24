<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["volunteer_loggedin"]) || $_SESSION["volunteer_loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include VolunteerEngagementCreation Class
require_once '../classes/VolunteerEngagementCreation.php';

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    // Check input errors before inserting in database
    if(isset($_POST["event_id"]) && isset($_POST["opportunity_id"]) && isset($_POST["volunteer_id"]) && isset($_POST["sponsor_id"]) && isset($_POST["contribution_value"]) && isset($_POST["needs_verification"]))
    {
        $obj = new VolunteerEngagementCreation($_POST["volunteer_id"]);

        $obj->setEventId($_POST["event_id"]);
        $obj->setOpportunityId($_POST["opportunity_id"]);
        $obj->setSponsorId($_POST["sponsor_id"]);
        $obj->setContributionValue($_POST["contribution_value"]);

        if(trim($_POST["needs_verification"]) == 0) {
            $obj->setStatus(1);
        }

        if($obj->isLimitPerVolunteerReached()) {
            // Sign-up Limit Per Volunteer Reached
            header("location: error-limit.php");
            exit();
        }
        else {
            if($obj->addEngagement()) {
                // Success

                $_SESSION["engagement_creation_success"] = true;

                header("location: dashboard.php");

                // $url = 'dashboard.php';
                // $data = array('creation' => 'success');

                // // use key 'http' even if you send the request to https://...
                // $options = array(
                //     'http' => array(
                //         'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                //         'method'  => 'POST',
                //         'content' => http_build_query($data)
                //     )
                // );
                // $context  = stream_context_create($options);
                // $result = file_get_contents($url, false, $context);
                // if ($result === FALSE) { /* Handle error */ }

                // var_dump($result);

                exit();


            }
            else {
                // Failure
                header("location: error.php");
                exit();
            }
        }
    }
}

?>
