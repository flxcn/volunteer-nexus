<?php
session_start();

// Make sure user is logged in
if(!isset($_SESSION["volunteer_loggedin"]) || $_SESSION["volunteer_loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "../config.php";

//Data Validation + SQL
if(isset($_GET["engagement_id"]) && isset($_GET["opportunity_id"])){

    $sql = "SELECT * FROM opportunities WHERE opportunity_id = ?";

    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $param_opportunity_id);

        //Set params
        $param_opportunity_id = trim($_GET["opportunity_id"]);

        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result) == 1){
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                $sponsor_id = $row["sponsor_id"];
                $role_name = $row["role_name"];
                $description = $row["description"];
                $start_date = $row["start_date"];
                $start_time = $row["start_time"];
                $end_date = $row["end_date"];
                $end_time = $row["end_time"];
                $total_positions = $row["total_positions"];
                $contribution_value = $row["contribution_value"];
                //{7} $positions_available = $[];

            } else{
                //NOTE: Error!
                header("Location: error.php");
                exit();
            }

        } else{
            echo "ERROR! Something went wrong...";
        }
    }

    mysqli_stmt_close($stmt);
    mysqli_close($link);

} else{
    //NOTE: Error!
    header("Location: error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Engagement</title>

    <!--Load required libraries-->
    <?php include '../head.php'?>
    
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h1>View Engagement</h1>
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
                        <label>Contribution Value</label>
                        <p class="form-control-static"><?php echo $row["contribution_value"]; ?></p>
                    </div>

                    <p>
                      <a href='dashboard.php' class="btn btn-primary">Back</a>
                    </p>
                </div>
            </div>

        </div>
    </div>
    <?php include '../footer.php';?>
</body>
</html>
