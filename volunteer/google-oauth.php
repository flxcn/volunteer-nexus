<?php

// Google API configuration
define('GOOGLE_CLIENT_ID', 'Google_Client_ID_Value');
define('GOOGLE_CLIENT_SECRET', 'Google_Client_Secret_Value');
define('GOOGLE_REDIRECT_URL', 'http:/localhost/volunteer-nexus-master/volunteers/google-oauth.php');

// Start session
if(session_status() !== PHP_SESSION_ACTIVE) session_start();


// Include Google API client library
require_once '../google-api-php-client/Google_Client.php';
require_once '../google-api-php-client/contrib/Google_Oauth2Service.php';

// Call Google API
$gClient = new Google_Client();
$gClient->setApplicationName('VolunteerNexus Login');
$gClient->setClientId(GOOGLE_CLIENT_ID);
$gClient->setClientSecret(GOOGLE_CLIENT_SECRET);
$gClient->setRedirectUri(GOOGLE_REDIRECT_URL);

$google_oauthV2 = new Google_Oauth2Service($gClient);

// Include User library file
require_once '../classes/VolunteerGoogleAuthentication.php';

if(isset($_GET['code'])){
    $gClient->authenticate($_GET['code']);
    $_SESSION['token'] = $gClient->getAccessToken();
    header('Location: ' . filter_var(GOOGLE_REDIRECT_URL, FILTER_SANITIZE_URL));
}

if(isset($_SESSION['token'])){
    $gClient->setAccessToken($_SESSION['token']);
}

if($gClient->getAccessToken()){
  // Get user profile data from google
  $gpUserProfile = $google_oauthV2->userinfo->get();

  // Initialize User class
  $obj = new VolunteerGoogleAuthentication();

  // Getting user profile info
	$obj->setOAuthUId($gpUserProfile['id']);
	$obj->setUsername($gpUserProfile['email']);
	$obj->setFirstName($gpUserProfile['given_name']);
	$obj->setLastName($gpUserProfile['family_name']);

	if($obj->authenticate()) {
		// Start a new session
		if(session_status() !== PHP_SESSION_ACTIVE) session_start();

		// Store data in session variables
		$_SESSION["volunteer_loggedin"] = true;
		$_SESSION["volunteer_id"] = $obj->getVolunteerId();
		$_SESSOPM["username"] = $obj->getUsername();
		$_SESSION["first_name"] = $obj->getFirstName();
		$_SESSION["last_name"] = $obj->getLastName();
		//$_SESSION["graduation_year"] = $obj->getGraduationYear();

		// Redirect user to dashboard
		header("location: dashboard.php");
	}
}
else {
    // Get login url
    $authUrl = $gClient->createAuthUrl();

    // Render google login button
    $output = '<a href="'.filter_var($authUrl, FILTER_SANITIZE_URL).'">Sign in with Google<img src="images/google-sign-in-btn.png" alt=""/></a>';
}
?>

<div class="container">
    <!-- Display login button / Google profile information -->
    <?php echo $output; ?>
</div>
