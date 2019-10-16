<?php

session_start();

//Check to make sure user is logged in
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
    header("Location: login.php");
    exit;
}

// Check for sponsor id
if(isset($_SESSION["sponsor_id"])){

    require_once "../config.php";

    $sql = "SELECT * FROM sponsors WHERE sponsor_id = ?";

    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $param_sponsor_id);

        $param_sponsor_id = trim($_SESSION["sponsor_id"]);

        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result) == 1){
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                $sponsor_name = $row["sponsor_name"];

            } else{
                // NOTE: ERROR!
                header("location: error.php");
                exit();
            }
        }
    }

    // Close SQL statement
    mysqli_stmt_close($stmt);

} else{
    //NOTE: ERROR!
    header("Location: error.php");
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
                        <p class="form-control-static"><?php echo $row["sponsor_name"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <p class="form-control-static"><?php echo $row["sponsor_name"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Start Date</label>
                        <p class="form-control-static"><?php echo $row["sponsor_name"]; ?></p>
                    </div>

                    <div class="form-group">
                        <label>Start Time</label>
                        <p class="form-control-static"><?php echo $row["sponsor_name"]; ?></p>
                    </div>

                    <div class="form-group">
                        <label>End Date</label>
                        <p class="form-control-static"><?php echo $row["sponsor_name"]; ?></p>
                    </div>

                    <div class="form-group">
                        <label>End Time</label>
                        <p class="form-control-static"><?php echo $row["sponsor_name"]; ?></p>
                    </div>

                    <div class="form-group">
                        <label>Total Positions</label>
                        <p class="form-control-static"><?php echo $row["sponsor_name"]; ?></p>
                    </div>

                    <p><a href='#' class="btn btn-primary">Edit</a></p>
                </div>
            </div>
        </div>
    </div>
		<?php include '../footer.php';?>
</body>
</html>
