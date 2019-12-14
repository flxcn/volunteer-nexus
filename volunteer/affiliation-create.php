<?php
session_start();

if(!isset($_SESSION["volunteer_loggedin"]) || $_SESSION["volunteer_loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "../config.php";

// Define variables and initialize with empty values
$volunteer_id = $_SESSION["volunteer_id"];
$sponsor_id = "";

$volunteer_id_error = "";
$sponsor_id_error = "";

$obj = new VolunteerAffiliation($volunteer_id);
$jsonSponsors = $obj->getEvents();

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

  // Set sponsor name
  $sponsor_id = trim($_POST["sponsor_id"]);
  $sponsor_id_error = $obj->setSponsorId($sponsor_id);

  if(empty($volunteer_id_error) && empty($sponsor_id_error)) {
    if($obj->checkAffiliationExists()) {
      $sponsor_id_error = "Affiliation already exists.";
      return;
    }

    if($obj->addAffiliation()) {
      header("location: dashboard.php");
    }
    else {
      echo "Error!";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Affiliation Sign Up</title>

    <!--Load required libraries-->
    <?php $pageContent='Form'?>
    <?php include '../head.php'?>

    <style type="text/css">
        .wrapper{ width: 350px; padding: 20px; }
    </style>

    <script type='text/javascript'>
      var sponsors = <?php echo $jsonSponsors;?>;

      function loadSponsors() {
        var select = document.getElementById("sponsorsSelect");
        for(var i = 0; i < sponsors.length; i++){
          select.options[i] = new Option(sponsors[i].sponsor_name, sponsors[i].sponsor_id);
        }
      }
    </script>
</head>
<body onload='loadSponsors();'>
    <div class="wrapper">
        <h2>Affiliation Sign Up</h2>
        <p>Please fill out this form to join an affiliation.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

          <!--form for sponsor_id-->
          <div class="form-group <?php echo (!empty($sponsor_id_error)) ? 'has-error' : ''; ?>">
              <label>Sponsor Name</label>
              <select name='sponsor_id' id='sponsorsSelect' class="form-control">
              </select>
              <span class="help-block"><?php echo $sponsor_id_error;?></span>
          </div>

          <div class="form-group">
              <input type="submit" class="btn btn-primary" value="Submit">
              <a href="dashboard.php" class="btn btn-default">Back</a>
          </div>

        </form>
    </div>
    <?php include '../footer.php';?>
</body>
</html>
