<?php
require_once '../vendor/autoload.php';

// init configuration
$clientID = '<YOUR_CLIENT_ID>';
$clientSecret = '<YOUR_CLIENT_SECRET>';
$redirectUri = 'http://localhost:8888/volunteer-nexus-master/volunteer/google-oauth.php';

// create Client Request to access Google API
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

// authenticate code from Google OAuth Flow
if (isset($_GET['code'])) {
  $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
  $client->setAccessToken($token['access_token']);

  // get profile info
  $google_oauth = new Google_Service_Oauth2($client);
  $google_account_info = $google_oauth->userinfo->get();
  $username =  $google_account_info->email;
  $first_name =  $google_account_info->given_name;
  $last_name =  $google_account_info->family_name;

  // now you can use this profile info to create account in your website and make user logged in.
  // Initialize User class
  require "../classes/VolunteerGoogleAuthentication.php";
  $obj = new VolunteerGoogleAuthentication();

  // Getting user profile info
	$obj->setUsername($username);
	$obj->setFirstName($first_name);
	$obj->setLastName($last_name);

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

} else {
  // echo
  //   "<!-- Compiled and minified CSS -->
  //   <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css'>
  //   <!-- Compiled and minified JavaScript -->
  //   <script src='https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js'></script>";

  echo
    "<br>
    <div class='col s12 m6 offset-m3 center-align'>
      <a class='oauth-container btn darken-4 white black-text' href='".$client->createAuthUrl() ."' style='text-transform:none; background-color: white; border-color: black;'>
        <div class='left'>
          <img width='20px' style='margin-top:7px; margin-right:8px' alt='Google sign-in' src='https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Google_%22G%22_Logo.svg/512px-Google_%22G%22_Logo.svg.png' />
        </div>
        Login with Google
      </a>
    </div>
    <br>";
}
?>
