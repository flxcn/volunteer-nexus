<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>VolunteerNexus</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
  <style type="text/css">
  body{ font: 14px sans-serif; text-align: center; }
  </style>
</head>

<body>
  <div class="page-header">
    <h1><b>VolunteerNexus</b></h1>
  </div>

  <p>
    <a href="volunteer/login.php" class="btn btn-success">Volunteer Login</a>
    <a href="sponsor/login.php" class="btn btn-success">Sponsor Login</a>
    <a href="volunteer/register.php" class="btn btn-success">Volunteer Register</a>
    <a href="sponsor/register.php" class="btn btn-success">Sponsor Register</a>
  </p>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <title>VolunteerNexus</title>
  <?php include 'head.php'?>

  <style>
  .bd-placeholder-img {
    font-size: 1.125rem;
    text-anchor: middle;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
  }

  @media (min-width: 768px) {
    .bd-placeholder-img-lg {
      font-size: 3.5rem;
    }
  }
  </style>

  <!-- Custom styles for this template -->
  <link href="css/index.css" rel="stylesheet">
</head>
<body class="text-center">
  <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
    <header class="masthead mb-auto">
      <div class="inner">
        <h3 class="masthead-brand">VolunteerNexus</h3>
        <nav class="nav nav-masthead justify-content-center">
          <a class="nav-link active" href="volunteer/login.php">Volunteer Login</a>
          <a class="nav-link" href="volunteer/register.php">Volunteer Register</a>|
          <a class="nav-link" href="sponsor/login.php">Sponsor Login</a>
          <a class="nav-link" href="sponsor/register.php">Sponsor Register</a>|
          <!-- <a class="nav-link" href="">About</a> -->
        </nav>
      </div>
    </header>

    <main role="main" class="inner cover">
      <h1 class="cover-heading">VolunteerNexus</h1>
      <p class="lead">Conveniently search and sign up for volunteer opportunities at Westlake High School. Find, join, and track service opportunities, all in one place.</p>
      <p class="lead">
        <a href="#" class="btn btn-lg btn-secondary">Sign up!</a>
      </p>
    </main>
  </div>
</body>
</html>
