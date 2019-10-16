<?php
session_start();

//Make sure user is logged in
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] == FALSE){
    header("location: login.php");
    exit;
}

require_once "../config.php";

if(isset($_GET["opportunity_id"])){

    $sql = "SELECT * FROM opportunities WHERE opportunity_id = ?";

    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $param_opportunity_id);

        // Set params
        $param_opportunity_id = trim($_GET["opportunity_id"]);

        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result) == 1){
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                $role_name = $row["role_name"];
                $description = $row["description"];
                $start_date = $row["start_date"];
                $start_time = $row["start_time"];
                $end_date = $row["end_date"];
                $end_time = $row["end_time"];
                $total_positions = $row["total_positions"];
                //{7} $positions_available = $[];

            } else{
                //NOTE: Error!
                header("location: error.php");
                exit();
            }

        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    mysqli_stmt_close($stmt);

} else{
    // NOTE: ERROR!
    header("Location: error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Opportunity</title>
    <!--Load required libraries-->
    <?php include '../head.php'?>
</head>
<body>

  <?php $thisPage='Events'; include 'navbar.php';?>

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
                    <!--This button does not work properly-->
                    <p><a href='event-read.php?event_id="<?php echo trim($_GET['event_id']); ?>"' class="btn btn-primary">Back</a></p>
                </div>
            </div>
        </div>
    </div>

    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Who's Signed Up?</h2>
                    </div>

                    <?php
                    $sql = "SELECT engagements.time_submitted AS time_submitted, engagements.opportunity_id AS opportunity_id, volunteers.first_name AS first_name, volunteers.last_name AS last_name, volunteers.username AS email_address, engagements.engagement_id AS engagement_id
                    FROM engagements LEFT JOIN volunteers ON volunteers.volunteer_id = engagements.volunteer_id
                    WHERE engagements.opportunity_id = '{$_GET['opportunity_id']}'
                    GROUP BY engagements.time_submitted, volunteers.first_name, volunteers.last_name, volunteers.username, engagements.engagement_id";

                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Time Submitted</th>";
                                        echo "<th>Name</th>";
                                        echo "<th>Email</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['time_submitted'] . "</td>";
                                        echo "<td>" . $row['last_name'] . ", " . $row['first_name'] . "</td>";
                                        echo "<td>" . $row['email_address'] . "</td>";
                                        echo "<td>";
                                            echo "<a href='engagement-delete.php?opportunity_id=". $row['opportunity_id'] ."&engagement_id=". $row['engagement_id'] ."' title='Delete Engagement' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";
                            echo "</table>";
                            mysqli_free_result($result);
                        } else{
                            echo "<p class='lead'><em>No opportunities were found.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                    }

                    mysqli_close($link);
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php include '../footer.php';?>
</body>
</html>
