<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Process delete operation after confirmation
if(isset($_POST["affiliation_id"]) && !empty($_POST["affiliation_id"])){
    // Include config file
    require_once "../classes/SponsorAffiliation.php";

    $sponsor_id = trim($_SESSION["sponsor_id"]);
    $affiliation_id = trim($_POST["affiliation_id"]);
    
    $obj = new SponsorAffiliation($sponsor_id);
    
    if($obj->removeAffiliation($affiliation_id))
    {
        header("location: affiliations.php");
        exit();
    }
    else {
        header("location: error.php");
        exit();
    }
}
else {
    // Check existence of id parameter
    if(empty(trim($_GET["affiliation_id"]))){
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Affiliation</title>

        <!--Load required libraries-->
        <?php include '../head.php'?>

    <style type="text/css">
        .wrapper{
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h1>Delete Affiliation</h1>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger fade in">
                            <input type="hidden" name="affiliation_id" value="<?php echo trim($_GET["affiliation_id"]); ?>"/>
                            <p>Are you sure you want to delete this affiliation? This action can be undone.</p><br>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="dashboard.php" class="btn btn-default">No</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
