<?php

// Google API configuration
define('GOOGLE_CLIENT_ID', 'Insert_Google_Client_ID');
define('GOOGLE_CLIENT_SECRET', 'Insert_Google_Client_Secret');
define('GOOGLE_REDIRECT_URL', 'Callback_URL');

// Start session
if(!session_id()){
    session_start();
}

// Include Google API client library
require_once 'google-api-php-client/Google_Client.php';
require_once 'google-api-php-client/contrib/Google_Oauth2Service.php';

// Call Google API
$gClient = new Google_Client();
$gClient->setApplicationName('VolunteerNexus Login');
$gClient->setClientId(GOOGLE_CLIENT_ID);
$gClient->setClientSecret(GOOGLE_CLIENT_SECRET);
$gClient->setRedirectUri(GOOGLE_REDIRECT_URL);

$google_oauthV2 = new Google_Oauth2Service($gClient);

// Include User library file
require_once 'User.class.php';

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

    // $gpUserData = array();
    // $gpUserData['oauth_uid']  = !empty()?$gpUserProfile['id']:'';
    // $gpUserData['first_name'] = !empty($gpUserProfile['given_name'])?$gpUserProfile['given_name']:'';
    // $gpUserData['last_name']  = !empty($gpUserProfile['family_name'])?$gpUserProfile['family_name']:'';
    // $gpUserData['email']      = !empty($gpUserProfile['email'])?$gpUserProfile['email']:'';
    // //$gpUserData['gender']     = !empty($gpUserProfile['gender'])?$gpUserProfile['gender']:'';
    // //$gpUserData['locale']     = !empty($gpUserProfile['locale'])?$gpUserProfile['locale']:'';
    // //$gpUserData['picture']    = !empty($gpUserProfile['picture'])?$gpUserProfile['picture']:'';
    // //$gpUserData['link']       = !empty($gpUserProfile['link'])?$gpUserProfile['link']:'';
		// $gpUserData['oauth_provider'] = 'google';

    // Insert or update user data to the database
    // $userData = $user->checkUser($gpUserData);

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

    // Storing user data in the session
    // $_SESSION['userData'] = $userData;

    // Render user profile data
    // if(!empty($userData)){
    //     $output  = '<h2>Google Account Details</h2>';
    //     $output .= '<div class="ac-data">';
    //     $output .= '<img src="'.$userData['picture'].'">';
    //     $output .= '<p><b>Google ID:</b> '.$userData['oauth_uid'].'</p>';
    //     $output .= '<p><b>Name:</b> '.$userData['first_name'].' '.$userData['last_name'].'</p>';
    //     $output .= '<p><b>Email:</b> '.$userData['email'].'</p>';
    //     $output .= '<p><b>Gender:</b> '.$userData['gender'].'</p>';
    //     $output .= '<p><b>Locale:</b> '.$userData['locale'].'</p>';
    //     $output .= '<p><b>Logged in with:</b> Google</p>';
    //     $output .= '<p><a href="'.$userData['link'].'" target="_blank">Click to visit Google+</a></p>';
    //     $output .= '<p>Logout from <a href="logout.php">Google</a></p>';
    //     $output .= '</div>';
    // }else{
    //     $output = '<h3 style="color:red">Some problem occurred, please try again.</h3>';
    // }
}else{
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
