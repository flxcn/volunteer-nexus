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
    // Include config file
    require_once "../config.php";

    // Prepare a select statement
    $sql = "SELECT * FROM sponsors WHERE sponsor_id = ?";

    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_sponsor_id);

        // Set parameters
        $param_sponsor_id = trim($_SESSION["sponsor_id"]);

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                // Retrieve individual field value
                $sponsor_name = $row["sponsor_name"];
                $username = $row["username"];

            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: error.php");
                exit();
            }

        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Close statement
    mysqli_stmt_close($stmt);

    // Close connection
    //mysqli_close($link);
} else{
    // URL doesn't contain id parameter. Redirect to error page
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
                        <p class="form-control-static"><?php echo $row["sponsor_name"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <p class="form-control-static"><?php echo $row["username"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <p class="form-control-static"><a class="btn btn-link" href="reset.php"></a>Reset password</p>
                    </div>
                    <div class="form-group">
                        <label>Contribution Type</label>
                        <p class="form-control-static"><?php echo $row["contribution_type"]; ?></p>
                    </div>

                    <div class="form-group">
                        <label>Advisor #1</label>
                        <p class="form-control-static"><?php echo $row["advisor1_name"]; ?></p>
                        <p class="form-control-static"><?php echo $row["advisor1_email"]; ?></p>
                        <p class="form-control-static"><?php echo $row["advisor1_phone"]; ?></p>
                    </div>

                    <div class="form-group">
                        <label>Advisor #2</label>
                        <p class="form-control-static"><?php echo $row["advisor2_name"]; ?></p>
                        <p class="form-control-static"><?php echo $row["advisor2_email"]; ?></p>
                        <p class="form-control-static"><?php echo $row["advisor2_phone"]; ?></p>
                    </div>

                    <div class="form-group">
                        <label>Advisor #3</label>
                        <p class="form-control-static"><?php echo $row["advisor3_name"]; ?></p>
                        <p class="form-control-static"><?php echo $row["advisor3_email"]; ?></p>
                        <p class="form-control-static"><?php echo $row["advisor3_phone"]; ?></p>
                    </div>

                    <!-- <p><a href='#' class="btn btn-primary">Edit</a></p> -->
                </div>
            </div>
        </div>
    </div>
		<?php include '../footer.php';?>
</body>
</html>
