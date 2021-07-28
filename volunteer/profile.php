<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["volunteer_loggedin"]) || $_SESSION["volunteer_loggedin"] !== true){
    header("location: sign-in.php");
    exit;
}

// Check existence of id parameter before processing further
if(isset($_SESSION["volunteer_id"])) {
    
    $volunteer_id = $_SESSION["volunteer_id"];
    require_once "../classes/VolunteerAccountReader.php";
    $obj = new VolunteerAccountReader($volunteer_id);

    $obj->getVolunteerDetails();

} else {
    header("location: error.php");
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Felix Chen">
    
    <title>Profile</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom styles for this template -->
    <link href="../assets/css/main.css" rel="stylesheet">
</head>
<body>
    <?php $thisPage='Profile'; include 'navbar.php';?>

    <div class="container-fluid">
        <div class="row">
            <main class="ms-sm-auto px-md-4">
                
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Profile</h1>
                </div>

                <div class="row">
                    <div class="col-sm-6 col-lg-4 mb-4">
                        <div class="card text-center border-primary">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $obj->getFullName(); ?></h5>
                                <p class="card-text"><?php echo $obj->getUsername(); ?></p>
                                <p class="card-text"><small class="text-muted">Member since <?php echo $obj->getTimeCreated(); ?></small></p>
                                <hr>
                                <p class="card-text"><?php echo $obj->getGraduationYear(); ?></p>
                                <p class="card-text">Student ID: <?php echo $obj->getStudentId(); ?></p>
                                <div class="btn-group text-center mb-2 mb-md-0">
                                    <a href="profile-update.php" class="btn btn-sm btn-outline-primary">Update profile</a>  
                                    <a href="reset.php" class="btn btn-sm btn-outline-primary">Reset password</a>  
                                </div>
                            </div>
                        </div>
                    </div>
    
                    <div class="col-sm-6 col-lg-8 mb-4">
                        <div class="card p-3">
                            <figure class="p-3 mb-0">
                                <blockquote class="blockquote">
                                    <p>The broadest, and maybe the most meaningful definition of volunteering: doing more than you have to because you want to, in a cause you consider good.</p>
                                </blockquote>
                                <figcaption class="blockquote-footer mb-0 mt-1 text-muted">
                                    Ivan Scheier
                                </figcaption>
                            </figure>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="card text-center my-2 bg-dark text-white" id="volunteer_id">
                            <div class="card-body">
                                <h5 class="card-title">Volunteer ID</h5>
                                <p class="card-text"><small><i>AttendanceAnywhere</i></small></p>
                            </div>
                            <?php include "attendance-anywhere.php"; ?>
                        </div>
                    </div>
                </div>

            </main>

            <?php include "footer.php" ?>
        </div>
    </div>

    <script src="../assets/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
