<header class="print">
    <nav class="navbar navbar-expand-md navbar-dark bg-dark" aria-label="navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">
            <img src="../assets/images/volunteernexus-logo-1.png" alt="" width="24" height="24" class="d-inline-block align-text-center">    
            VolunteerNexus
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample03" aria-controls="navbarsExample03" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarsExample03">
                <ul class="navbar-nav me-auto mb-2 mb-sm-0">
                    <li class="nav-item">
                        <a class="nav-link <?php if ($thisPage === 'Dashboard') {echo 'active';} ?>" aria-current="page" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if ($thisPage === 'Events') {echo 'active';} ?>" href="events.php">Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if ($thisPage === 'Tutoring') {echo 'active';} ?>" href="../tutor/dashboard.php">Tutoring</a>
                    </li>
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <li class="nav-item">
                        <a class="nav-link <?php if ($thisPage === 'Profile') {echo 'active';} ?>" href="profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sign-out.php">Sign out</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>