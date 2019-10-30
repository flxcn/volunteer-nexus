<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["admin_loggedin"]) || $_SESSION["admin_loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include config file
require_once '../config.php';

// Define variables and initialize with empty values
$admin_id = $_SESSION["admin_id"];
$sql = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

      $input_sql = trim($_POST["sql"]);

      $sql = $input_sql;

      if($stmt = mysqli_prepare($link, $sql)){
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: console.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later. If the issue persists, send an email to felix@volunteernexus.com detailing the problem.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Query Database</title>
    <!--Load required libraries-->
    <?php $pageContent='Form'?>
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
                        <h2>Query Database</h2>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($sql_error)) ? 'has-error' : ''; ?>"> <!-- NOTE:see {2} -->
                            <label>Query</label>
                            <textarea type="text" name="sql" class="form-control" rows="4" cols="50" value="<?php echo $sql; ?>"></textarea>
                            <span class="help-block"><?php echo $sql_error;?></span>
                        </div>

                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="dashboard.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include '../footer.php';?>
</body>
</html>
