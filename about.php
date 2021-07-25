<?php
require "classes/NexusOverview.php";
$obj = new NexusOverview();
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="VolunteerNexus offers a centralized platform for students to discover, join, and track volunteer opportunities in their community.">
        <meta name="author" content="Felix Chen">
        <link rel="icon" type="image/png" href="assets/images/volunteernexus-logo-1.png">
    
        <title>About</title>

        <!-- Bootstrap core CSS -->
        <link href="assets/bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Custom styles for this template -->
        <link href="assets/css/about.css" rel="stylesheet">
    </head>

    <body class="text-center">
        <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
            <header class="masthead mb-auto">
                <div class="inner">
                    <h3 class="masthead-brand mb-2"><img class="border rounded border-white" src="assets/images/volunteernexus-logo-1.png" width="50px"></h3>
                    <nav class="nav nav-masthead justify-content-center">
                        <a class="nav-link" href="index.php">Home</a>
                        <a class="nav-link active" href="#">About</a>
                        <a class="nav-link" href="sponsor/login.php">For Sponsors</a>
                         <a class="nav-link" href="https://github.com/flxcn/volunteer-nexus/wiki">Wiki</a>
                        <a class="nav-link" href="https://github.com/flxcn/volunteer-nexus/wiki/Privacy-Policy.md">Privacy</a>
                    </nav>
                </div>
            </header>

            <main role="main" class="mb-3">

                <!-- <div class="row mt-2">
                    <div class="col-md col-xs-12 m-2">
                        <img src="assets/images/Chen,Felix-Headshot.JPG" width="200px" height="200px" ><img>
                    </div>
                    <div class="col-md col-xs-12 m-2">
                    <p>Felix Chen is a rising senior at Westlake High School who is passionate about finding new solutions through technology. The need for the solution provided by the VolunteerNexus was made evident through his experience working with numerous student organizations at Westlake High School. You can see some of his other coding projects at his GitHub profile, linked below.</p>
                    </div>
                </div> -->

                <!-- <p class="my-3">VolunteerNexus began in 2019 to improve student access to service opportunities across Westlake High School.</p> -->

                <hr>

                <h5 class="m-sm-3">So far, we have connected...</h5>


                <div class="row">
                    <div class="card col-md col-xs-6 m-2">

                        <div class="card-body text-dark">
                        <p class="card-text display-3"><b><?php echo $obj->countSponsors(); ?></b></p>
                        <p>Organizations</p>

                        </div>
                    </div>

                    <div class="card col-md col-xs-6 m-2">
                        <div class="card-body text-dark">
                        <p class="card-text display-3"><b><?php echo $obj->formatDisplayNumber($obj->countVolunteers()); ?></b></p>
                        <p>Volunteers</p>
                        </div>
                    </div>

                    <div class="card col-md col-xs-6 m-2">
                        <div class="card-body text-dark">
                        <p class="card-text display-3"><b><?php echo $obj->formatDisplayNumber($obj->countEngagements()); ?></b></p>
                        <p class="p-0">Engagements</p>                        
                    </div>
                    </div>
                </div>
            </main>

            <footer class="mastfoot mt-auto">
                <div class="inner">
                <p>Built by <a href="https://felixchen.com" target="_blank">Felix Chen</a>.</p>
                </div>
            </footer>
        </div>
    </body>
</html>
