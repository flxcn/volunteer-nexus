<?php

session_start();

if(!isset($_SESSION["volunteer_loggedin"]) || $_SESSION["volunteer_loggedin"] !== true){
    header("location: sign-in.php");
    exit;
}

require_once "../classes/VolunteerAccountUpdate.php";

$obj = new VolunteerAccountUpdate($_SESSION["volunteer_id"]);
$obj->getVolunteerDetails();

// Define variables and initialize with empty values
$graduation_year = $obj->getGraduationYear();
$student_id = $obj->getStudentId();

$error = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Set graduation_year
    $graduation_year = trim($_POST["graduation_year"]);
    $error .= $obj->setGraduationYear($graduation_year);

    // Set student_id
    $student_id = trim($_POST["student_id"]);
    $error .= $obj->setStudentId($student_id);

    if(empty($error))
    {
        if($obj->updateStudentId() && $obj->updateGraduationYear()) {
            header("location: profile.php");
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

        <div class="text-danger text-center"><?php echo $error; ?></div>

        <div class="row">
            <div class="col-md-12 d-flex justify-content-center order-md-1">
                <form class="needs-validation" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate="" oninput='confirm_password.setCustomValidity(confirm_password.value != password.value ? "Passwords do not match." : "")'>
                
                    <div class="mb-3">
                        <label for="school">School</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="Westlake High School" id="school" name="school" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="student_id">Student ID</label>
                        <input type="text" class="form-control" id="student_id" name="student_id" placeholder="#####" value="<?php echo $student_id; ?>" >
                    </div>

                    <div class="mb-3">
                        <label for="graduation_year">Graduation Year</label>
                        <select class="form-select d-block w-100" id="graduation_year" name="graduation_year" required="">
                            <option value="">Choose...</option>
                            <option value="00"    <?php if($graduation_year == 0) echo "selected"; ?>>N/A</option> 
                            <option value="2020" <?php if($graduation_year == 2020) echo "selected"; ?>>2020</option>
                            <option value="2021" <?php if($graduation_year == 2021) echo "selected"; ?>>2021</option>
                            <option value="2022" <?php if($graduation_year == 2022) echo "selected"; ?>>2022</option>
                            <option value="2023" <?php if($graduation_year == 2023) echo "selected"; ?>>2023</option>
                            <option value="2024" <?php if($graduation_year == 2024) echo "selected"; ?>>2024</option>
                            <option value="2025" <?php if($graduation_year == 2025) echo "selected"; ?>>2025</option> 
                            <option value="2026" <?php if($graduation_year == 2026) echo "selected"; ?>>2026</option> 
                            <option value="2027" <?php if($graduation_year == 2027) echo "selected"; ?>>2027</option> 
                            <option value="2028" <?php if($graduation_year == 2028) echo "selected"; ?>>2028</option> 
                            <option value="2029" <?php if($graduation_year == 2029) echo "selected"; ?>>2029</option> 
                            <option value="2030" <?php if($graduation_year == 2030) echo "selected"; ?>>2030</option> 
                        </select>
                        <div class="invalid-feedback">
                            Please select a graduation year.
                        </div> 
                    </div>

                    

                    <hr class="mb-4">

                    <button class="w-100 btn btn-primary btn-lg btn-block" type="submit">Update!</button>
                    <a class="w-100 btn btn-link btn-block" href="profile.php">Go back</a>

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