<?php
session_start();

if(!isset($_SESSION["volunteer_loggedin"]) || $_SESSION["volunteer_loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AttendanceAnywhere</title>
    <!--Load required libraries-->
    <?php include '../head.php'?>
    <script src="../EasyQRCodeJS/easy.qrcode.js" type="text/javascript" charset="utf-8"></script>
</head>
<body>
    <?php $thisPage='Dashboard'; include 'navbar.php';?>

    <div class="page-header">
        <h1><b>AttendanceAnywhere</b></h1>
        <p>Get this Volunteer ID scanned to track your attendance at opportunities.</p>
        <p>If you want to save this to your camera roll, just press and hold on the image and click "Save image"</p>
    </div>

    <body>
  		<div id="container">
  			<div id="generated_qr_code"></div>
  		</div>
  	</body>

    <?php include '../footer.php';?>
</body>
</html>

<script type="text/javascript">
  // define QR Code content
	var school = "whs";
	var volunteerId = "<?php echo $_SESSION["volunteer_id"];?>";
	var qrCodeContent = school + "_" + volunteerId;

  // define full name
	var name = "" + "<?php echo $_SESSION["last_name"];?>" + ", " + "<?php echo $_SESSION['first_name']?>";

	var config = {
		// QR Code Content (Volunteer ID)
		text: qrCodeContent,

		// Title (Volunteer Name)
		title: name,
		titleFont: "bold 30px Arial",
		titleColor: "#fff",
		titleBackgroundColor: "#000094",
		titleHeight: 70,
		titleTop: 60,

		// Subtitle (VolunteerNexus)
		subTitle: "VolunteerNexus",
		subTitleFont: "24px Arial",
		subTitleColor: "#ffffff",
		subTitleTop: 40,

		// QR Code Formatting
		width: 480,
		height: 480,
		colorDark: "#000000",
		colorLight: "#ffffff",

		// QR Code Quiet Zone Formatting
    quietZone: 80,
    quietZoneColor: "#ffffff",

		// Logo
		logo: "../images/logo.png",
		logoWidth: 150,
		logoHeight: 150,
		logoBackgroundColor: '#ffffff', // Invalid when `logBgTransparent` is true; default is '#ffffff'
		logoBackgroundTransparent: false, // When transparent image is used, default is false

		// Correction Level
		correctLevel: QRCode.CorrectLevel.H // L, M, Q, H
	};

	// Generate QR Code
	new QRCode(document.getElementById("generated_qr_code"), config);
</script>
