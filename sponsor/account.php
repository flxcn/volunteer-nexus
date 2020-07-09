<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Check existence of id parameter before processing further
if(isset($_SESSION["sponsor_id"])){

    $sponsor_id = $_SESSION["sponsor_id"];
    require_once "../classes/SponsorAccountReader.php";
    $obj = new SponsorAccountReader($sponsor_id);

    $obj->getSponsorDetails();

} else {
    header("location: error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Details</title>

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

  <?php $thisPage='Account'; include 'navbar.php';?>

    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h1>Account Details</h1>
                    </div>
                    <div class="form-group">
                        <label>Sponsor Name</label>
                        <p class="form-control-static"><?php echo $obj->getSponsorName(); ?></p>
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <p class="form-control-static"><?php echo $obj->getUsername(); ?></p>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <p class="form-control-static"><a class="btn btn-link" href="reset.php">Reset password</a></p>
                    </div>
                    <div class="form-group">
                        <label>Contribution Type</label>
                        <p class="form-control-static"><?php echo $obj->getContributionType(); ?></p>
                    </div>

                    <div class="form-group">
                        <label>Advisor #1</label>
                        <p class="form-control-static"><?php echo $obj->getAdvisor1Name(); ?></p>
                        <p class="form-control-static"><?php echo $obj->getAdvisor1Email(); ?></p>
                        <p class="form-control-static"><?php echo $obj->getAdvisor1Phone(); ?></p>
                    </div>

                    <div class="form-group">
                        <label>Advisor #2</label>
                        <p class="form-control-static"><?php echo $obj->getAdvisor2Name(); ?></p>
                        <p class="form-control-static"><?php echo $obj->getAdvisor2Email(); ?></p>
                        <p class="form-control-static"><?php echo $obj->getAdvisor2Phone(); ?></p>
                    </div>

                    <div class="form-group">
                        <label>Advisor #3</label>
                        <p class="form-control-static"><?php echo $obj->getAdvisor3Name(); ?></p>
                        <p class="form-control-static"><?php echo $obj->getAdvisor3Email(); ?></p>
                        <p class="form-control-static"><?php echo $obj->getAdvisor3Phone(); ?></p>
                    </div>

                    <div class="form-group">
                        <p class="form-control-static"><i><b>VolunteerNexus</b> member since <?php echo $obj->getTimeCreated(); ?></i></p>
                    </div>

                    <!-- <p><a href='#' class="btn btn-primary">Edit</a></p> -->
                </div>
            </div>
        </div>
    </div>
		<?php include '../footer.php';?>
</body>
</html>
