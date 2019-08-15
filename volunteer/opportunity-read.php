<?php

// Check existence of id parameter before processing further
if(isset($_GET["event_id"]) && isset($_GET["opportunity_id"])){
    // Include config file
    require_once "../config.php";

    // Prepare a select statement
    $sql = "SELECT * FROM opportunities WHERE opportunity_id = ?";

    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_opportunity_id);

        // Set parameters
        $param_opportunity_id = trim($_GET["opportunity_id"]);
        $param_event_id = trim($_GET["event_id"]);

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                // Retrieve individual field value
                $role_name = $row["role_name"];
                $description = $row["description"];
                $start_date = $row["start_date"];
                $start_time = $row["start_time"];
                $end_date = $row["end_date"];
                $end_time = $row["end_time"];
                $total_positions = $row["total_positions"];
                //{7} $positions_available = $[];

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



    if($_SERVER["REQUEST_METHOD"] == "POST"){

        // Validate event name
        $student_id = trim($_POST["student_id"]);


        // Check input errors before inserting in database
        if(isset($_GET["opportunity_id"]) && isset($_SESSION["student_id"])){
          // Prepare an insert statement
            $sql = "INSERT INTO engagements (opportunity_id, student_id) VALUES (?, ?)";

            if($stmt = mysqli_prepare($link, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ii", $param_opportunity_id, $param_student_id);

                // Set parameters
                $param_opportunity_id = $_GET["opportunity_id"];
                $param_student_id = $_SESSION["student_id"];

                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    // Records created successfully. Redirect to landing page
                    header("location: events.php"); //NOTE: this can redirect to my upcoming events page
                    exit();
                } else{
                    echo "Something went wrong. Please try again later. If the issue persists, send an email to westlakestuco@gmail.com detailing the problem.";
                }
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }

    }
    // Close connection
    mysqli_close($link);

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
    <title>View Opportunity</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
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
                        <h1>View Opportunity</h1>
                    </div>
                    <div class="form-group">
                        <label>Role Name</label>
                        <p class="form-control-static"><?php echo $row["role_name"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <p class="form-control-static"><?php echo $row["description"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Start Date</label>
                        <p class="form-control-static"><?php echo $row["start_date"]; ?></p>
                    </div>

                    <div class="form-group">
                        <label>Start Time</label>
                        <p class="form-control-static"><?php echo $row["start_time"]; ?></p>
                    </div>

                    <div class="form-group">
                        <label>End Date</label>
                        <p class="form-control-static"><?php echo $row["end_date"]; ?></p>
                    </div>

                    <div class="form-group">
                        <label>End Time</label>
                        <p class="form-control-static"><?php echo $row["end_time"]; ?></p>
                    </div>

                    <div class="form-group">
                        <label>Total Positions</label>
                        <p class="form-control-static"><?php echo $row["total_positions"]; ?></p>
                    </div>

                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <input type="reset" class="btn btn-default" value="Reset">
                    </div>
                    <!--This button does not work properly-->

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                      <!--put all the values as hidden, then have the button submit to php file somewhere to add an engagement-->
                        <input type="hidden" name="student_id" value="<?php echo $_SESSION["student_id"]; ?>"/>
                      <input type="submit" class="btn btn-primary" value="Sign Up!">
                    </form>
                    <p>
                      <a href='event-read.php?event_id="<?php echo $_GET["event_id"]; ?>"' class="btn btn-primary">Back</a>
                    </p>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
