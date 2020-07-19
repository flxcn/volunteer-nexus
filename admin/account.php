
<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["admin_loggedin"]) || $_SESSION["admin_loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Check existence of id parameter before processing further
if(isset($_SESSION["admin_id"])){

    $admin_id = $_SESSION["admin_id"];
    require_once "../classes/AdminAccountReader.php";
    $obj = new AdminAccountReader($admin_id);

    $obj->getAdminDetails();

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
                        <label>Admin Name</label>
                        <p class="form-control-static"><?php echo $obj->getAdminName(); ?></p>
                    </div>

                    <div class="form-group">
                        <label>Username</label>
                        <p class="form-control-static"><?php echo $obj->getUsername(); ?></p>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <p class="form-control-static"><a class="btn btn-link" href="reset.php">Reset password</a></p>
                    </div>


                    <!-- NOTE: When the platform has multiple admins, it may be useful to have a duration of access for each user -->
                    <div class="form-group">
                        <label>Duration of Access</label>
			<p class="form-control-static"><?php echo $obj->getAccessDuration(); ?></p>
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
