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
    <link href="../assets/css/register.css" rel="stylesheet">
</head>

<body class="bg-light">
    
    <div class="container">
        <div class="py-5 text-center">
            <img class="d-block mx-auto mb-4" src="../assets/images/volunteernexus-logo-1.png" alt="" width="72" height="72">
            <h2>Volunteer Registration</h2>
            <p class="lead">Fill out this form to create your <i><b>VolunteerNexus</b></i> account.</p>
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
                        <label for="committee_id">Graduation Year</label>
                        <select class="form-select d-block w-100" id="committee_id" name="committee_id" required="">
                            <option value="">Choose...</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option> 
                            <option value="0">N/A</option> 
                        </select>
                        <div class="invalid-feedback">
                        Please select a committee.
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

                    <button class="btn btn-primary btn-lg btn-block" type="submit">Register!</button>
                </form>
            </div>
        </div>

        <footer class="my-5 pt-5 text-muted text-center text-small">
            <p class="mb-1">&copy; 2021 Felix Chen</p>
            <!-- <ul class="list-inline">
            <li class="list-inline-item"><a href="https://getbootstrap.com/docs/4.6/examples/checkout/#">Privacy</a></li>
            <li class="list-inline-item"><a href="https://getbootstrap.com/docs/4.6/examples/checkout/#">Terms</a></li>
            <li class="list-inline-item"><a href="https://getbootstrap.com/docs/4.6/examples/checkout/#">Support</a></li>
            </ul> -->
        </footer>
    </div>

    <!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script> -->

    <!-- Custom js for this page -->
    <script src="../assets/js/register.js"></script>
</body>
</html>