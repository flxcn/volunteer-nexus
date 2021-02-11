<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["volunteer_loggedin"]) || $_SESSION["volunteer_loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Check existence of id parameter before processing further
if(isset($_SESSION["volunteer_id"])) {
    $volunteer_id = $_SESSION["volunteer_id"];
} else {
    header("location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tutoring</title>

    <!--Load required libraries-->
    <?php include '../head.php'?>

    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <?php $thisPage='Tutoring'; include 'navbar.php';?>

    

    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">

                    <div class="page-header">
                        <h1>Tutoring</h1>
                    </div>

                    <p>
                        <a href="tutorial-engagement-create.php" class="btn btn-success">Record your contributions!</a>
                    </p>

                    <h2>How it works:</h2>

                    <h3>1. Register.</h3>
                    <p>
                    If you want to become a tutor through a Sponsor, fill out one of the corresponding forms below linked <a href="#tutor-links">here</a> (or scroll down). Then, the Sponsor will pair you up with a student seeking tutoring help.
                    </p>

                    <p>
                    If you want to be tutored through a specific Sponsor, fill out one of the corresponding forms below linked <a href="#tutor-links">here</a> (or scroll down). Then, the Sponsor will pair you up with a student who can help.
                    </p>

                    <h3>2. Tutor</h3>
                    <p>
                    Connect with students interested in receiving tutoring help, and work closely to help them understand the material. A list of tutoring guidelines can be found <a href="tutoring-guidelines.php">here</a>.
                    </p>

                    <h3>3. Record</h3>
                    <p>
                    Take a picture of yourself during tutoring as proof of participation. Send it to your respective Sponsor through their designated process.
                    </p>
                    <p>
                    Use the button above labeled <b><a href="tutor-engagement-create.php">"Record your contributions!"</a></b> and fill out the linked form. Your affiliated Sponsor will confirm your participation, and your volunteering will be recognized! Thank you for taking the time to support the learning of others! Once approved, your tutoring work will show up in your contribution history, linked <a href="dashboard.php#affiliations">here</a>.
                    </p>

                    <div class="page-header">
                        <h2>Registration Links</h2>
                    </div>

                    <!-- For Tutors -->
                    <table class='table table-bordered' name="tutor-links">
                        <thead>
                            <tr>
                                <th colspan="2">Becoming a tutor</th>
                            </tr>
                            <tr>
                                <th>Sponsor</th>
                                <th>Registration Form</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>Latin Club</td>
                                <td><a href="https://forms.gle/qzKbZJnHmaJn9zWJ7" class="btn btn-info" target="_blank">Form Link</a></td>
                            </tr>
                            <tr>
                                <td>Mu Alpha Theta</td>
                                <td><a href="" class="btn btn-info" target="_blank">Coming soon...</a></td>
                            </tr>
                            <tr>
                                <td>National Honor Society</td>
                                <td><a href="https://docs.google.com/forms/d/e/1FAIpQLSf4yKcHbHCD5JWqBNhmFtA2vk5U85YPdg8ZnfoBHrbtRLrvrA/viewform" class="btn btn-info" target="_blank">Form Link</a></td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- For Tutees -->
                    <table class='table table-bordered' name="tutee-links">
                        <thead>
                            <tr>
                                <th colspan="2">Requesting tutoring help</th>
                            </tr>
                            <tr>
                                <th>Sponsor</th>
                                <th>Registration Form</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>Latin Club</td>
                                <td><a href="https://forms.gle/XvNBo98oXvcyXM5z7" class="btn btn-info" target="_blank">Form Link</a></td>
                            </tr>
                            <tr>
                                <td>Mu Alpha Theta</td>
                                <td><a href="" class="btn btn-info" target="_blank">Coming soon...</a></td>
                            </tr>
                            <tr>
                                <td>National Honor Society</td>
                                <td><a href="https://docs.google.com/forms/d/e/1FAIpQLSfHrdlxC_hoDwuh9WevL-wMAichgwHO2aY3oaJsDPvDmsD0bA/viewform?usp=sf_link" class="btn btn-info" target="_blank">Form Link</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
	
    <?php include '../footer.php';?>
</body>
</html>
