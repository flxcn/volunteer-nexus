<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
    header("location: sign-in.php");
    exit;
}

// Process delete operation after confirmation
if(isset($_POST["event_id"]) && !empty($_POST["event_id"])) {

    require_once "../classes/SponsorEvent.php";
    $obj = new SponsorEvent($_SESSION['sponsor_id']);

    $event_id = trim($_POST["event_id"]);
    if($obj->removeEvent($event_id)) {
        header("location: events.php");
        exit();
    } 
    else {
        echo "Oops! Something went wrong. Please try again later.";
    }
  
} 
else {
    // Check existence of id parameter
    if(empty(trim($_GET["event_id"]))) {
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
    
    <title>Delete event</title>

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
                    <h5 class="card-header bg-danger text-light">Delete event</h5>
                    <div class="card-body">
                        <p class="card-text">Are you sure you want to delete this event? This cannot be undone.</p>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <input type="hidden" name="event_id" value="<?php echo trim($_GET["event_id"]); ?>"/>
                            <input type="submit" value="Yes" class="btn btn-danger"> 
                            <a href="events.php" class="btn btn-outline-secondary">No</a>
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
