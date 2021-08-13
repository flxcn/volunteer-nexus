<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["volunteer_loggedin"]) || $_SESSION["volunteer_loggedin"] !== true){
    header("location: sign-in.php");
    exit;
}

// Process delete operation after confirmation
if(isset($_POST["affiliation_id"]) && !empty($_POST["affiliation_id"])){

    require_once "../classes/VolunteerAffiliation.php";

    $volunteer_id = trim($_SESSION["volunteer_id"]);
    $affiliation_id = trim($_POST["affiliation_id"]);
    
    $obj = new VolunteerAffiliation($volunteer_id);
    
    if($obj->removeAffiliation($affiliation_id))
    {
        header("location: dashboard.php");
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

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Felix Chen">
    
    <title>Leave sponsor</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom styles for this template -->
    <link href="../assets/css/main.css" rel="stylesheet">
</head>
<body>
    <?php $thisPage='Events'; include 'navbar.php';?>

    <div class="container-fluid">
        <div class="row">
            <main class="ms-sm-auto px-md-4">
                <div class="col-12 col-md-6 card mt-3 mx-auto border-danger">
                    <h5 class="card-header bg-danger text-light">Leave sponsor</h5>
                    <div class="card-body">
                        <p class="card-text">Are you sure you want to leave this Sponsor? You can rejoin later.</p>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <input type="hidden" name="affiliation_id" value="<?php echo trim($_GET["affiliation_id"]); ?>"/>
                            <input type="submit" value="Yes" class="btn btn-danger"> 
                            <a href="dashboard.php" class="btn btn-outline-secondary">No</a>
                        </form>
                    </div>
                </div>
            </main>

            <?php include "footer.php"; ?>
        </div>
    </div>

    <script src="../assets/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

