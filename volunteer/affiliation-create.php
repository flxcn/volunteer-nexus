<?php

session_start();

if(!isset($_SESSION["volunteer_loggedin"]) || $_SESSION["volunteer_loggedin"] !== true){
    header("location: sign-in.php");
    exit;
}

require_once '../classes/VolunteerAffiliation.php';

// Define variables and initialize with empty values
$volunteer_id = $_SESSION["volunteer_id"];
$sponsor_id = "";

$volunteer_id_error = "";
$sponsor_id_error = "";
$error = "";

$obj = new VolunteerAffiliation($volunteer_id);
$jsonSponsors = $obj->getSponsors();

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Set sponsor name
    $sponsor_id = trim($_POST["sponsor_id"]);
    $sponsor_id_error = $obj->setSponsorId($sponsor_id);

    if(empty($volunteer_id_error) && empty($sponsor_id_error)) {
        if($obj->checkAffiliationExists()) {
            $error .= "You've already joined this Sponsor.";
        }
        else {
            if($obj->addAffiliation()) {
            header("location: dashboard.php");
            }
            else {
                echo "Error!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <link rel="icon" type="image/png" href="assets/images/volunteernexus-logo-1.png">

    <title>Affiliation Sign Up</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../assets/css/form.css" rel="stylesheet">

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

<body class="bg-light" onload='loadSponsors();'>
    
    <div class="container">
        <div class="py-5 text-center">
            <img class="d-block mx-auto mb-4" src="../assets/images/volunteernexus-logo-1.png" alt="" width="72" height="72">
            <h2>Join a Sponsor</h2>
            <p class="lead">After joining, you'll be able to see a Sponsor's events and opportunities.v</p>
        </div>
        <div class="row">
            <div class="col-md-12 d-flex justify-content-center order-md-1">
                <form class="needs-validation" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate="" oninput='confirm_password.setCustomValidity(confirm_password.value != password.value ? "Passwords do not match." : "")'>
                
                    <div class="mb-3">
                        <label for="graduation_year">Sponsor Name</label>
                        <select class="form-select d-block w-100" id="sponsorsSelect" name="sponsor_id" required="">
                        </select>
                        <div class="invalid-feedback">
                            Please select a sponsor.
                        </div> 
                    </div>

                    <div class="text-danger text-center"><?php echo $error; ?></div>

                    <hr class="mb-4">

                    <button class="w-100 btn btn-primary btn-lg btn-block" type="submit">Join!</button>
                    <a class="w-100 btn btn-link btn-block" href="dashboard.php">Go back</a>
                </form>
            </div>
        </div>

        <?php include "footer.php"; ?>
    </div>

    <!-- Custom js for this page -->
    <script src="../assets/js/form.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script>
        $("body").on("submit", "form", function() {
            $(this).submit(function() {
                return false;
            });
            return true;
        });
    </script>
</body>
</html>