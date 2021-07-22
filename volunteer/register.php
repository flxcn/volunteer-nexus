<?php

session_start();

if(isset($_SESSION["volunteer_loggedin"]) && $_SESSION["volunteer_loggedin"] === true){
    header("location: dashboard.php");
    exit;
}

require_once "../classes/VolunteerRegistration.php";

// Define variables and initialize with empty values
$username = "";
$password = "";
$confirm_password = "";
$graduation_year = "";
$first_name = "";
$last_name = "";

$error = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $obj = new VolunteerRegistration();

    // Set first_name
    $first_name = trim($_POST["first_name"]);
    $error .= $obj->setFirstName($first_name);

    // Set last_name
    $last_name = trim($_POST["last_name"]);
    $error .= $obj->setLastName($last_name);

    // Set graduation_year
    $graduation_year = trim($_POST["graduation_year"]);
    $error .= $obj->setGraduationYear($graduation_year);

    // Set username
    $username = trim($_POST["username"]);
    $error .= $obj->setUsername($username);

    // Set password
    $password = trim($_POST["password"]);
    $error .= $obj->setPassword($password);

    // Set confirm_password
    $confirm_password = trim($_POST["confirm_password"]);
    $error .= $obj->setConfirmPassword($confirm_password);

    if(empty($error))
    {
        if($obj->addVolunteer()) {
        header("location: sign-in.php");
        }
        else {
            echo "Something went wrong. Please try again later.";
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

    <title>Register</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../assets/css/form.css" rel="stylesheet">
</head>

<body class="bg-light">
    
    <div class="container">
        <div class="py-5 text-center">
            <img class="d-block mx-auto mb-4" src="../assets/images/volunteernexus-logo-1.png" alt="" width="72" height="72">
            <h2>Volunteer Registration</h2>
            <p class="lead">Fill out this form to create your <i><b>VolunteerNexus</b></i> account.<br>Already have an account? <a href="sign-in.php">Sign in here</a>.</p>
        </div>

        <div class="text-danger text-center"><?php echo $error; ?></div>

        <div class="row">
            <div class="col-md-12 d-flex justify-content-center order-md-1">
                <form class="needs-validation" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate="" oninput='confirm_password.setCustomValidity(confirm_password.value != password.value ? "Passwords do not match." : "")'>
                
                    <h4 class="mb-3">Volunteer details</h4>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name">First name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="" value="" required="">
                            <div class="invalid-feedback">
                                Valid first name is required.
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name">Last name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="" value="" required="">
                            <div class="invalid-feedback">
                                Valid last name is required.
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="school">School</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="Westlake High School" id="school" name="school" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="graduation_year">Graduation Year</label>
                        <select class="form-select d-block w-100" id="graduation_year" name="graduation_year" required="">
                            <option value="">Choose...</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option> 
                            <option value="0">N/A</option> 
                        </select>
                        <div class="invalid-feedback">
                            Please select a graduation year.
                        </div> 
                    </div>

                    <hr class="mb-4">

                    <h4 class="mb-3">Sign-in details</h4>

                    <div class="mb-3">
                        <label for="username">Email (Username)</label>
                        <input type="email" class="form-control" id="username" name="username" placeholder="you@example.com" required="">
                        <div class="invalid-feedback">
                            Please enter a valid email address for your username.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required="">
                        <div class="invalid-feedback">
                            Please enter a password for your email username.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="confirm_password">Confirm password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm password" required="">
                        <div class="invalid-feedback">
                            Passwords do not match.
                        </div>
                    </div>

                    <hr class="mb-4">

                    <button class="w-100 btn btn-primary btn-lg btn-block" type="submit">Create your account!</button>
                </form>
            </div>
        </div>

        <footer class="my-5 pt-5 text-muted text-center text-small">
            <p class="mb-1">&copy; 2021 Felix Chen</p>
        </footer>
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