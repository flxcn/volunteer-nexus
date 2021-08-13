<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
    header("location: sign-in.php");
    exit;
}

// Process delete operation after confirmation
if(isset($_POST["opportunity_id"]) && !empty($_POST["opportunity_id"]) && isset($_POST["event_id"]) && !empty($_POST["event_id"])){

    require_once "../classes/SponsorOpportunity.php";

    $sponsor_id = trim($_SESSION["sponsor_id"]);
    $opportunity_id = trim($_POST["opportunity_id"]);
    
    $obj = new SponsorOpportunity($sponsor_id);
    
    if($obj->removeOpportunity($opportunity_id))
    {
        header("location: event-read.php?event_id=" . $_POST['event_id']);
        exit();
    }
    else {
        header("location: error.php");
        exit();
    }
}
else {
    // Check existence of id parameter
    if(empty(trim($_GET["opportunity_id"]))) {
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
    
    <title>Delete opportunity</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom styles for this template -->
    <link href="../assets/css/main.css" rel="stylesheet">
    <link rel="stylesheet" media="print" href="../assets/css/print.css" />
</head>
<body>
    <?php $thisPage='Events'; include 'navbar.php';?>

    <div class="container-fluid">
        <div class="row">
            <main class="ms-sm-auto px-md-4">
                <div class="col-12 col-md-6 card mt-3 mx-auto border-danger">
                    <h5 class="card-header bg-danger text-light">Delete opportunity</h5>
                    <div class="card-body">
                        <p class="card-text">Are you sure you want to delete this opportunity? This cannot be undone.</p>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <input type="hidden" name="opportunity_id" value="<?php echo trim($_GET["opportunity_id"]); ?>"/>
                            <input type="hidden" name="event_id" value="<?php echo trim($_GET["event_id"]); ?>"/>
                            <input type="submit" value="Yes" class="btn btn-danger">
                            <a href="event-read.php?event_id=<?php echo trim($_GET["event_id"]); ?>" class="btn btn-outline-secondary">No</a>
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