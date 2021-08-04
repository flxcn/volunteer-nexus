<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["volunteer_loggedin"]) || $_SESSION["volunteer_loggedin"] !== true){
    header("location: ../volunteer/sign-in.php");
    exit;
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

    <title>Request a Tutor</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../assets/css/form.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->


    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.1.1/dist/select2-bootstrap-5-theme.min.css" />
</head>

<body class="bg-light" onload='loadSponsors();'>
    
    <div class="container">
        <div class="py-3 text-center">
            <img class="d-block mx-auto mb-4" src="../assets/images/volunteernexus-logo-1.png" alt="" width="72" height="72">
            <h2>Request a Tutor</h2>
            <!-- <p class="lead">Please fill this form and submit to request engagement for tutoring.</p> -->
        </div>

        <div class="text-danger text-center"><?php echo $error; ?></div>

        <div class="row">
            <div class="col-md-12 d-flex justify-content-center order-md-1">
                <form class="needs-validation" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate="">
                
                    <h4 class="mb-3">Learner Details</h4>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name">First name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="" value="<?php echo $_SESSION['first_name'];?>">
                        
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name">Last name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="" value="<?php echo $_SESSION['last_name'];?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="username">Email You Check</label>
                        <input type="email" class="form-control" id="username" name="username" placeholder="you@example.com" required="">
                        <div class="invalid-feedback">
                            Please enter a valid email address.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="phone_number">Your Phone Number <small>(Format: 123-456-7890)</small></label>
                        <input type="tel" class="form-control" id="contribution_value" name="phone_number" placeholder="###-###-####" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" required>
                        <div class="invalid-feedback">
                            Please enter a valid email address for your username.
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

                    <div class="mb-3">
                        <label for="student_id">Student ID</label>
                        <input type="text" class="form-control" id="student_id" name="student_id" placeholder="#####" value="<?php echo $student_id; ?>" >
                    </div>

                    <h4 class="mb-3">Parent Details</h4>

                    <div class="mb-3">
                        <label for="first_name">Parent's Full Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="" value="">
                    </div>

                    <div class="mb-3">
                        <label for="username">Parent's Email</label>
                        <input type="email" class="form-control" id="username" name="username" placeholder="you@example.com" required="">
                        <div class="invalid-feedback">
                            Please enter a valid email address.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="phone_number">Parent's Phone Number <small>(Format: 123-456-7890)</small></label>
                        <input type="tel" class="form-control" id="contribution_value" name="phone_number" placeholder="###-###-####" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" required>
                        <div class="invalid-feedback">
                            Please enter a valid email address for your username.
                        </div>
                    </div>

                    <h4 class="mb-3">Tutoring Focus</h4>

                    <div class="mb-3">
                        <!-- data-allow-clear="1" -->
                        <label for="subjects">What subject do you need tutoring for?</label>
                        <select class="form-select w-100 js-example-basic-multiple" data-placeholder="Choose anything"  id="subjects" name="states[]" id="js-example-basic-multiple" multiple="multiple">
                            <optgroup label="Math">
                                <option value="AL">Geometry</option>
                                <option value="OZ">Algebra I</option>
                                <option value="WY">Algebra II</option>
                            </optgroup>

                            <optgroup label="Social Studies">
                                <option value="AL">Human Geography</option>
                                <option value="OZ">Government</option>
                                <option value="WY">US History</option>
                            </optgroup>
                        </select>
                    </div>

                    <h4 class="mb-3">Availability</h4>

                    <table class="table table-bordered table-sm border-dark align-middle text-center">
                        <thead class="table-dark">
                            <td class="col-3" scope="col">Day</td>
                            <td class="col-3" scope="col">Morning</td>
                            <td class="col-3" scope="col">After School</td>
                            <td class="col-3" scope="col">Evening</td>
                        </thead>
                        <tbody>
                            <tr>
                                <td scope="row">Mon.</td>
                                <td>
                                    <input class="form-check-input" type="checkbox" value="" id="elem">
                                </td>
                                <td>
                                    <input class="form-check-input" type="checkbox" value="" id="elem">
                                </td>
                                <td>
                                    <input class="form-check-input" type="checkbox" value="" id="elem">
                                </td>
                            </tr>
                            <tr>
                                <td scope="row">Tue.</td>
                                <td>
                                    <input class="form-check-input" type="checkbox" value="" id="elem">
                                </td>
                                <td>
                                    <input class="form-check-input" type="checkbox" value="" id="elem">
                                </td>
                                <td>
                                    <input class="form-check-input" type="checkbox" value="" id="elem">
                                </td>
                            </tr>
                            <tr>
                                <td scope="row">Wed.</td>
                                <td>
                                    <input class="form-check-input" type="checkbox" value="" id="elem">
                                </td>
                                <td>
                                    <input class="form-check-input" type="checkbox" value="" id="elem">
                                </td>
                                <td>
                                    <input class="form-check-input" type="checkbox" value="" id="elem">
                                </td>
                            </tr>
                            <tr>
                                <td scope="row">Thu.</td>
                                <td>
                                    <input class="form-check-input" type="checkbox" value="" id="elem">
                                </td>
                                <td>
                                    <input class="form-check-input" type="checkbox" value="" id="elem">
                                </td>
                                <td>
                                    <input class="form-check-input" type="checkbox" value="" id="elem">
                                </td>
                            </tr>
                            <tr>
                                <td scope="row">Fri.</td>
                                <td>
                                    <input class="form-check-input" type="checkbox" value="" id="elem">
                                </td>
                                <td>
                                    <input class="form-check-input" type="checkbox" value="" id="elem">
                                </td>
                                <td>
                                    <input class="form-check-input" type="checkbox" value="" id="elem">
                                </td>
                            </tr>
                        </tbody>
                    </table> 

                    <hr class="mb-4">

                    <button class="w-100 btn btn-primary btn-lg btn-block" type="submit">Record your tutoring!</button>
                </form>
            </div>
        </div>

        <footer class="my-5 pt-5 text-muted text-center text-small">
            <p class="mb-1">&copy; 2021 Felix Chen</p>
        </footer>
    </div>

    <!-- Custom js for this page -->
    <!-- <script src="../assets/js/form.js"></script> -->
    <!-- jQuery -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script> -->
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- select2-bootstrap-5-theme -->
    <script src="/select2-bootstrap-5-theme/script.js"></script>
    
    <script>

        function select2( size ) {
            $( ".js-example-basic-multiple" ).each( function () {
                $( this ).select2( {
                    theme: "bootstrap-5",
                    width: $( this ).data( "width" ) ? $( this ).data( "width" ) : $( this ).hasClass( "w-100" ) ? "100%" : "style",
                    placeholder: $( this ).data( "placeholder" ),
                    allowClear: Boolean( $( this ).data( "allow-clear" ) ),
                    closeOnSelect: !$( this ).attr( "multiple" ),
                    containerCssClass: size == "small" || size == "large" ? "select2--" + size : "",
                    selectionCssClass: size == "small" || size == "large" ? "select2--" + size : "",
                    dropdownCssClass: size == "small" || size == "large" ? "select2--" + size : "",
                } );
            } );
        }

        select2()

        var buttons = document.querySelectorAll(".select2-size")

        buttons.forEach( function( button ) {
            var id = button.id
            button.addEventListener( "click", function( e ) {
                e.preventDefault()
                select2( id )
                document.querySelectorAll(".select2-size").forEach( function( item ) {
                    item.classList.remove( "active" )
                } )

                this.classList.add( "active" )
            } )
        } )

        // $(document).ready(function() {
        //     $('.js-example-basic-multiple').select2();
        // });

        // Basic
        $(".js-example-basic-multiple").select2({
            theme: "bootstrap-5",
        });

        // Small using Bootstrap 5 classes
        // $("#form-select-sm").select2({
        //     theme: "bootstrap-5",
        //     dropdownParent: $("#form-select-sm").parent(), // Required for dropdown styling
        // });

        // Large using Select2 properties
        // $(".js-example-basic-multiple").select2({
        //     theme: "bootstrap-5",
        //     containerCssClass: "select2--large", // For Select2 v4.0
        //     selectionCssClass: "select2--large", // For Select2 v4.1
        //     dropdownCssClass: "select2--large",
        // });

        $("body").on("submit", "form", function() {
            $(this).submit(function() {
                return false;
            });
            return true;
        });
    </script>

    <script type='text/javascript'>
        <?php
            echo "var sponsors = $jsonSponsors; \n";
        ?>

        function loadSponsors(){
            console.log(sponsors);
            var select = document.getElementById("sponsorsSelect");
            for(var i = 0; i < sponsors.length; i++){
                var sponsorValue = [sponsors[i].sponsor_name, sponsors[i].sponsor_id];
                select.options[i] = new Option(sponsors[i].sponsor_name, JSON.stringify(sponsorValue));
            }
        }
    </script>
</body>
</html>