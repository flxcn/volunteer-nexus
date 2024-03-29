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

        // Redirect user to dashboard
        header("location: dashboard.php");
    }

} else {

    $auth_url = $client->createAuthUrl();

}
?>
