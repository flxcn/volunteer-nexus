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
    
    <title>Tutoring</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom styles for this template -->
    <link href="../assets/css/main.css" rel="stylesheet">
</head>
<body>
    <?php $thisPage='Tutoring'; include '../volunteer/navbar.php';?>

    <div class="container-fluid">
        <div class="row">
            <main class="ms-sm-auto px-md-4">
                
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Tutoring</h1>
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
                                    <a href="tutor-create.php" class="btn btn-sm btn-outline-primary">Register as a tutor</a>                                      
                                    <a href="../volunteer/affiliation-create.php" class="btn btn-sm btn-outline-primary">Register with a sponsor</a>  
                                    <a href="tutor-request.php" class="btn btn-sm btn-outline-primary">Request tutoring</a>  
                                    <a href="tutorial-create.php" class="btn btn-sm btn-outline-primary">Log tutoring hours</a>  
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-8 mb-4">
                        <div class="card border-dark m-2">
                            <div class="card-body">
                                <h5 class="card-title">Assignments</h5>
                                <p class="card-text">No current assignments</p>
                            </div>
                        </div>

                        <div class="row mx-auto">
                            <div class="card col border-dark m-2">
                                <div class="card-body">
                                    <h5 class="card-title">Assignments</h5>
                                    <p class="card-text">No current assignments</p>
                                </div>
                            </div>
                            <div class="card col border-dark m-2">
                                <div class="card-body">
                                    <h5 class="card-title">Assignments</h5>
                                    <p class="card-text">No current assignments</p>
                                </div>
                            </div>
                            <div class="card col border-dark m-2">
                                <div class="card-body">
                                    <h5 class="card-title">Assignments</h5>
                                    <p class="card-text">No current assignments</p>
                                </div>
                            </div>
                        </div>
                    </div>
    
                </div>

            </main>

            <?php include "../volunteer/footer.php" ?>
        </div>
    </div>

    <script src="../assets/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
