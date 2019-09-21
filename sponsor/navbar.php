<nav class="navbar navbar-inverse">
<div class="container-fluid">
	<div class="navbar-header">

		<!--hamburger-->
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#theNavbar">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>

		<!--logo-->
		<a class="navbar-brand" href="dashboard.php">VolunteerNexus</a>

	</div>

	<!-- links -->
	<div class="collapse navbar-collapse" id="theNavbar">

		<ul class="nav navbar-nav">
			<li <?php if ($thisPage === 'Dashboard') {echo 'class="active"';} ?>><a href="dashboard.php">Dashboard</a></li>
			<li <?php if ($thisPage === 'Events') {echo 'class="active"';} ?>><a href="events.php">Events</a></li>
			<li <?php if ($thisPage === 'Engagements') {echo 'class="active"';} ?>><a href="engagements.php">Engagements</a></li>
			<li <?php if ($thisPage === 'Affiliations') {echo 'class="active"';} ?>><a href="affiliations.php">Affiliations</a></li>
		</ul>

		<ul class="nav navbar-nav navbar-right">
			<li <?php if ($thisPage === 'Account') {echo 'class="active"';} ?>><a href="reset.php"><span class="glyphicon glyphicon-user"></span> Account</a></li>
			<li><a href="../logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
		</ul>

	</div>
</div>
</nav>
