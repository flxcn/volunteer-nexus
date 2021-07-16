<?php
// session_start();

// if(!isset($_SESSION["admin_loggedin"]) || $_SESSION["admin_loggedin"] !== true){
//     header("location: login.php");
//     exit;
// }

require "../classes/AdminDashboard.php";

$obj = new AdminDashboard();
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Felix Chen">
        
        <title>Admin Dashboard</title>

        <!-- Bootstrap core CSS -->
        <link href="../assets/bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">

        <style>
            .bd-placeholder-img {
                font-size: 1.125rem;
                text-anchor: middle;
                -webkit-user-select: none;
                -moz-user-select: none;
                user-select: none;
            }

            @media (min-width: 768px) {
                .bd-placeholder-img-lg {
                font-size: 3.5rem;
                }
            }
        </style>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        
        <!-- Custom styles for this template -->
        <link href="dashboard.css" rel="stylesheet">
    </head>

    <body>
    
        <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
            <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">VolunteerNexus</a>
            <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <span class="w-100"></span>
            <div class="navbar-nav">
                <div class="nav-item text-nowrap">
                <a class="nav-link p-3" href="#">Sign out</a>
                <!-- this one is still in an awkward spot -->
                </div>
            </div>
        </header>

        <div class="container-fluid">
            <div class="row">
                
                <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                    <div class="position-sticky pt-3">
                        <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">
                            <span data-feather="home"></span>
                            Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                            <span data-feather="file"></span> Events
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                            <span data-feather="shopping-cart"></span>
                            Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                            <span data-feather="users"></span> Volunteers
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">
                            <!-- <span data-feather="person-bounding-box"></span> -->
                            <i class="bi-person-bounding-box me-2"></i> A/A
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                            <!-- <span data-feather="bi-person-badge"></span> -->
                            <i class="bi-person-badge-fill me-2"></i> A/A
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi-person-circle"></i>

                            <!-- <span data-feather="person-circle"></span> -->
                            Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi-people"></i>

                            <!-- <span data-feather="people"></span> -->
                            Affiliations
                            </a>
                        </li>
                        </ul>

                        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                        <span>Engagements</span>
                        <a class="link-secondary" href="#" aria-label="Add a new report">
                            <span data-feather="plus-circle"></span>
                        </a>
                        </h6>
                        <ul class="nav flex-column mb-2">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                            <span data-feather="file-text"></span>
                            Latest
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                            <span data-feather="file-text"></span>
                            Pending
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                            <span data-feather="file-text"></span>
                            Social engagement
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                            <span data-feather="file-text"></span>
                            Year-end sale
                            </a>
                        </li>
                        </ul>
                    </div>
                </nav>

                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">Dashboard</h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary"><span data-feather="share"></span> Share</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()"><span data-feather="printer"></span> Export</button>
                        </div>
                        </div>
                    </div>

                    <div class="row my-4">
                        
                        <div class="col-12 col-md-6 mb-4 mb-lg-0 col-lg-3">
                            <div class="card">
                                <h5 class="card-header">Volunteers</h5>
                                <div class="card-body">
                                <h5 class="card-title"><?php echo $obj->countVolunteers(); ?></h5>
                                <p class="card-text text-success">4.6% increase this month</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mb-4 mb-lg-0 col-lg-3">
                            <div class="card">
                                <h5 class="card-header">Engagements</h5>
                                <div class="card-body">
                                <h5 class="card-title"><?php echo $obj->countEngagements(); ?></h5>
                                <p class="card-text text-success">+128 since yesterday</p>
                                </div>
                                <!-- <a href="#" class="btn btn-block btn-light card-footer">View all</a> -->

                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-3 mb-4 mb-lg-0">
                            <div class="card">
                                <h5 class="card-header">Sponsors</h5>
                                <div class="card-body">
                                <h5 class="card-title"><?php echo $obj->countSponsors(); ?></h5>
                                <!-- <p class="card-text">Feb 1 - Apr 1, United States</p> -->
                                <!-- <p class="card-text text-success">18.2% increase since last month</p> -->
                                </div>
                                <a href="sponsors.php" class="btn btn-block btn-light card-footer">View all</a>

                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-3 mb-4 mb-lg-0">
                            <div class="card">
                                <h5 class="card-header">Opportunities</h5>
                                <div class="card-body">
                                <h5 class="card-title"><?php echo $obj->countOpportunities(); ?></h5>
                                <!-- <p class="card-text">Feb 1 - Apr 1, United States</p> -->
                                <!-- <p class="card-text text-success">18.2% increase since last month</p> -->
                                </div>
                                <a href="opportunities.php" class="btn btn-block btn-light card-footer">View all</a>

                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12 col-xl-12 mb-4 mb-lg-0">
                            <div class="card">
                                <h5 class="card-header">Latest events</h5>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th scope="col">Name</th>
                                                <th scope="col">Sponsor</th>
                                                <th scope="col">Contact</th>
                                                <th scope="col">Date</th>
                                                <th scope="col"></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <th scope="row">Lemonade Stand</th>
                                                <td>Student Council</td>
                                                <td>johndoe@gmail.com</td>
                                                <td>Aug 31 2020</td>
                                                <td><a href="#" class="btn btn-sm btn-primary">View</a></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Apple Bobbing</th>
                                                <td>National Honor Society</td>
                                                <td>jacob.monroe@company.com</td>
                                                <td>Aug 28 2020</td>
                                                <td><a href="#" class="btn btn-sm btn-primary">View</a></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Lemonade Stand</th>
                                                <td>Student Council</td>
                                                <td>johndoe@gmail.com</td>
                                                <td>Aug 31 2020</td>
                                                <td><a href="#" class="btn btn-sm btn-primary">View</a></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Apple Bobbing</th>
                                                <td>National Honor Society</td>
                                                <td>jacob.monroe@company.com</td>
                                                <td>Aug 28 2020</td>
                                                <td><a href="#" class="btn btn-sm btn-primary">View</a></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <a href="#" class="btn btn-block btn-light card-footer">View all</a>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>


        <script src="../assets/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script>
        <script src="dashboard.js"></script>
    </body>
</html>
