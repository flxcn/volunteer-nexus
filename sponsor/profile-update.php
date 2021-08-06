<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
    header("location: sign-in.php");
    exit;
}

// Include class
require_once "../classes/SponsorAccountUpdate.php";

$sponsor_id = $_SESSION["sponsor_id"];

$obj = new SponsorAccountUpdate($sponsor_id);

$sponsor_name = "";
$username = "";
$contribution_type = "";
$advisor1_name = "";
$advisor1_email = "";
$advisor1_phone = "";
$advisor2_name = "";
$advisor2_email = "";
$advisor2_phone = "";
$advisor3_name = "";
$advisor3_email = "";
$advisor3_phone = "";

// Define variables and initialize with empty values
$sponsor_name_error = "";
$username_error = "";
$contribution_type_error = "";
$advisor1_name_error = "";
$advisor1_email_error = "";
$advisor1_phone_error = "";
$advisor2_name_error = "";
$advisor2_email_error = "";
$advisor2_phone_error = "";
$advisor3_name_error = "";
$advisor3_email_error = "";
$advisor3_phone_error = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Set sponsor_name
    $sponsor_name = trim($_POST["sponsor_name"]);
    $sponsor_name_error = $obj->setSponsorName($sponsor_name);

    // Set username
    $username = trim($_POST["username"]);
    $username_error = $obj->setUsername($username);

    // Set contribution_type
    $contribution_type = trim($_POST["contribution_type"]);
    $contribution_type_error = $obj->setContributionType($contribution_type);

    // Set advisor1 information
    $advisor1_name = $obj->setAdvisor1Name(($_POST["advisor1_name"]));
    $advisor1_email = $obj->setAdvisor1Email(trim($_POST["advisor1_email"]));
    $advisor1_phone = $obj->setAdvisor1Phone(trim($_POST["advisor1_phone"]));

    // Set advisor2 information
    $advisor2_name = $obj->setAdvisor2Name(trim($_POST["advisor2_name"]));
    $advisor2_email = $obj->setAdvisor2Email(trim($_POST["advisor2_email"]));
    $advisor2_phone = $obj->setAdvisor2Phone(trim($_POST["advisor2_phone"]));

    // Set advisor3 information
    $advisor3_name = $obj->setAdvisor3Name(trim($_POST["advisor3_name"]));
    $advisor3_email = $obj->setAdvisor3Email(trim($_POST["advisor3_email"]));
    $advisor3_phone = $obj->setAdvisor3Phone(trim($_POST["advisor3_phone"]));

    if(empty($username_error) && empty($contribution_type_error) && empty($sponsor_name_error)  && empty($advisor1_name_error) && empty($advisor1_email_error) && empty($advisor1_phone_error))
    {
        if($obj->updateSponsor()) {
            header("location: profile.php");
        }
        else {
            echo "Something went wrong. Please try again later.";
        }
    }
}
else {

    if($obj->getSponsor()) {
        $sponsor_name = $obj->getSponsorName(); 
        $username = $obj->getUsername();
        $contribution_type = $obj->getContributionType();
        $advisor1_name = $obj->getAdvisor1Name();
        $advisor1_phone = $obj->getAdvisor1Phone();
        $advisor1_email = $obj->getAdvisor1Email();
        $advisor2_name = $obj->getAdvisor2Name();
        $advisor2_phone = $obj->getAdvisor2Phone();
        $advisor2_email = $obj->getAdvisor2Email();
        $advisor3_name = $obj->getAdvisor3Name();
        $advisor3_phone = $obj->getAdvisor3Phone();
        $advisor3_email = $obj->getAdvisor3Email();
    }
    else{
        echo "Existing sponsor details unavailable.";
        exit();
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

    <title>Update Profile</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../assets/css/form.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="py-5 text-center">
            <img class="d-block mx-auto mb-4" src="../assets/images/volunteernexus-logo-1.png" alt="" width="72" height="72">
            <h2>Update Profile</h2>
        </div>
        
        <div class="row">
            <div class="col-md-12 d-flex justify-content-center order-md-1">
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post" class="row g-2">

                    <!--form for sponsor_name-->
                    <div class="row"> 
                        <div class="mb-3">
                            <label for="sponsor_name">Sponsor (Organization) Name</label>
                            <div class="input-group">
                                <input required type="text" name="sponsor_name" id="sponsor_name" class="form-control" placeholder="Organization Name" value="<?php echo $sponsor_name; ?>">
                            </div>
                            <span class="help-block text-danger"><?php echo $sponsor_name_error;?></span>
                        </div>
                    </div>

                    <!--form for username-->
                    <div class="row"> 
                        <div class="mb-3">
                            <label for="username">Username (Email Address)</label>
                            <div class="input-group">
                                <input required type="email" name="username" id="username" size="30" class="form-control" placeholder="Email" value="<?php echo $username; ?>">
                            </div>
                            <span class="help-block text-danger"><?php echo $username_error; ?></span>
                        </div>
                    </div>


                    <!--form for contribution type-->
                    <div class="row">
                        <div class="mb-3">
                            <label for="contribution_type">Contribution Type (points, hours, etc.)</label>
                            <div class="input-group">
                                <input type="text" name="contribution_type" id="contribution_type" class="form-control" value="<?php echo $contribution_type; ?>">
                            </div>
                            <span class="help-block text-danger"><?php echo $contribution_type_error;?></span>
                        </div>
                    </div>

                    <hr class="mb-3">

                    <div class="row">
                        <!--form for advisor1_name-->
                        <div class="mb-3 col-md-4">
                            <label for="advisor1_name">Teacher Advisor #1 Name</label>
                            <div class="input-group">
                                <input type="text" name="advisor1_name" id="advisor1_name" class="form-control" value="<?php echo $advisor1_name; ?>" >
                            </div>
                            <span class="help-block text-danger"><?php echo $advisor1_name_error;?></span>
                        </div>

                        <!--form for advisor1_phone-->
                        <div class="mb-3 col-md-4">
                            <label for="advisor1_phone">Teacher Advisor #1 Phone</label>
                            <div class="input-group">
                                <input type="tel" name="advisor1_phone" id="advisor1_phone" class="form-control" value="<?php echo $advisor1_phone; ?>">
                            </div>
                            <span class="help-block text-danger"><?php echo $advisor1_phone_error;?></span>
                        </div>

                        <!--form for advisor1_email-->
                        <div class="mb-3 col-md-4">
                            <label for="advisor1_email">Teacher Advisor #1 Email</label>
                            <div class="input-group">
                                <input type="email" name="advisor1_email" id="advisor1_email" class="form-control" value="<?php echo $advisor1_email; ?>">
                            </div>
                            <span class="help-block text-danger"><?php echo $advisor1_email_error;?></span>
                        </div>
                    </div>

                    <hr class="mb-3">

                    <div class="row">
                        <!--form for advisor2_name-->
                        <div class="mb-3 col-md-4">
                            <label for="advisor2_name">Teacher Advisor #2 Name</label>
                            <div class="input-group">
                                <input type="text" name="advisor2_name" id="advisor2_name" class="form-control" value="<?php echo $advisor2_name; ?>" >
                            </div>
                            <span class="help-block text-danger"><?php echo $advisor2_name_error;?></span>
                        </div>

                        <!--form for advisor2_phone-->
                        <div class="mb-3 col-md-4">
                            <label for="advisor2_phone">Teacher Advisor #2 Phone</label>
                            <div class="input-group">
                                <input type="tel" name="advisor2_phone" id="advisor2_phone" class="form-control" value="<?php echo $advisor2_phone; ?>">
                            </div>
                            <span class="help-block text-danger"><?php echo $advisor2_phone_error;?></span>
                        </div>

                        <!--form for advisor2_email-->
                        <div class="mb-3 col-md-4">
                            <label for="advisor2_email">Teacher Advisor #2 Email</label>
                            <div class="input-group">
                                <input type="email" name="advisor2_email" id="advisor2_email" class="form-control" value="<?php echo $advisor2_email; ?>">
                            </div>
                            <span class="help-block text-danger"><?php echo $advisor2_email_error;?></span>
                        </div>
                    </div>

                    <hr class="mb-3">

                    <div class="row">
                        <!--form for advisor3_name-->
                        <div class="mb-3 col-md-4">
                            <label for="advisor3_name">Teacher Advisor #3 Name</label>
                            <div class="input-group">
                                <input type="text" name="advisor3_name" id="advisor3_name" class="form-control" value="<?php echo $advisor3_name; ?>" >
                            </div>
                            <span class="help-block text-danger"><?php echo $advisor3_name_error;?></span>
                        </div>

                        <!--form for advisor2_phone-->
                        <div class="mb-3 col-md-4">
                            <label for="advisor3_phone">Teacher Advisor #3 Phone</label>
                            <div class="input-group">
                                <input type="tel" name="advisor3_phone" id="advisor3_phone" class="form-control" value="<?php echo $advisor3_phone; ?>">
                            </div>
                            <span class="help-block text-danger"><?php echo $advisor3_phone_error;?></span>
                        </div>

                        <!--form for advisor2_email-->
                        <div class="mb-3 col-md-4">
                            <label for="advisor3_email">Teacher Advisor #3 Email</label>
                            <div class="input-group">
                                <input type="email" name="advisor3_email" id="advisor3_email" class="form-control" value="<?php echo $advisor3_email; ?>">
                            </div>
                            <span class="help-block text-danger"><?php echo $advisor3_email_error;?></span>
                        </div>
                    </div>

                    <hr class="mb-3">

                    <button class="w-100 btn btn-primary btn-lg btn-block" type="submit">Update profile</button>

                    <a href="profile.php" class="btn btn-default">Cancel</a>
                </form>
            </div>
        </div>

        <?php include 'footer.php';?>

    </div>

    <!-- Custom js for this page -->
    <script src="../assets/js/form.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>

</body>
</html>
