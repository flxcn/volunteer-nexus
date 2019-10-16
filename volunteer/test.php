<?php
session_start();

// Make sure user is logged in
if(!isset($_SESSION["volunteer_loggedin"]) || $_SESSION["volunteer_loggedin"] == FALSE)
{
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Find Events</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<style type="text/css">

body
{
	height: 100%;
	font: 14px sans-serif;
	text-align: center;
	align-items: center;
	justify-content: center;
	background-color: #f5f5f5;
	margin-bottom: 60px;
}

.wrapper{

	/* centered in page */
	margin: 0 auto;
}

.page-header h2{
	margin-top: 0;
}

table
{
	/* set table color to white */
	background-color: #ffffff;
}

th
{
	text-align:center;
}

</style>
		<style>
			.col-sm d-none  {border-style: solid;}
		</style>
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</head>





<body>

  <!--Navigation Bar-->
  <?php $thisPage='Events'; include 'navbar.php';?>

    <div class="wrapper">
        <div class="container-fluid">
                    <div class="page-header clearfix">
                        <h2>Events</h2>
                    </div>

                    <?php
                    // Include config file
                    require_once "../config.php";
                    // Attempt select query execution
                    $sql = "SELECT * FROM events WHERE registration_start <= CURDATE() AND registration_end >= CURDATE() ORDER BY registration_end";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
																echo "<div class='row align-items-start'>";
																  echo "<div class='col-sm d-none d-sm-block border'>Event</div>";
																  echo "<div class='col-sm d-none d-sm-block border'>Sponsor</div>";
																	echo "<div class='col-sm d-none d-sm-block border'>Reg. Deadline</div>";
																	echo "<div class='col-sm d-none d-sm-block border'>Description</div>";
																	echo "<div class='col-sm d-none d-sm-block border'>Location</div>";
																	echo "<div class='col-sm d-none d-sm-block border'>Duration</div>";
																	echo "<div class='col-sm d-none d-sm-block border'>Action</div>";
																	echo "<div class='w-100'></div>";


                                while($row = mysqli_fetch_array($result)){


																	  echo "<div class='col-sm d-sm-none border'>Event:</div>";
																		echo "<div class='col-sm border'>" . $row['event_name'] . "</div>";
																		echo "<div class='w-100 d-sm-none'></div>";
																		echo "<div class='col-sm d-sm-none border'>Sponsor:</div>";
																		echo "<div class='col-sm border'>" . $row['sponsor_name'] . "</div>";
																		echo "<div class='w-100 d-sm-none'></div>";
																		echo "<div class='col-sm d-sm-none border'>Sign up by:</div>";
																		echo "<div class='col-sm border'>" . $row['registration_end'] . "</div>";
																		echo "<div class='w-100 d-sm-none'></div>";
																		echo "<div class='col-sm d-sm-none border'>Description:</div>";
																		echo "<div class='col-sm border'>" . $row['description'] . "</div>";
																		echo "<div class='w-100 d-sm-none'></div>";
																		echo "<div class='col-sm d-sm-none border'>Location:</div>";
																		echo "<div class='col-sm border'>" . $row['location'] . "</div>";
																		echo "<div class='w-100 d-sm-none'></div>";
																		echo "<div class='col-sm d-sm-none border'>Duration:</div>";
																		echo "<div class='col-sm border'>" . $row['event_start'] . " to " . $row['event_end'] . "</div>";
																		echo "<div class='w-100 d-sm-none'></div>";
																		echo "<div class='col-sm d-sm-none border'>Action:</div>";
																		echo "<div class='col-sm border'>";
																			echo "<a href='event-read.php?event_id=". $row['event_id'] ."' title='View Event' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a>";
																		echo "</div>";
																		echo "<div class='w-100'></div>";

                                }
																echo "</div>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo "<p class='lead'><em>No events were found.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                    }

                    // Close connection
                    mysqli_close($link);
                    ?>

        </div>
    </div>

    <?php include '../footer.php';?>
</body>
</html>
