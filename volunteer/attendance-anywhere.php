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
  <style>
	h1 {
    font-size: 2.5em;
    margin-left: 0;
    margin-right: 0;
    font-weight: bold;
  }
  </style>
  <script src="../EasyQRCodeJS/easy.qrcode.js" type="text/javascript" charset="utf-8"></script>
</head>
<body>
  <?php $thisPage='Dashboard'; include 'navbar.php';?>
  <div class="wrapper">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="page-header">
              <h1><b>AttendanceAnywhere</b></h1>
              <p>Get this Volunteer ID scanned to track your attendance at opportunities.</p>
              <p>If you want to save this to your camera roll, just press and hold on the image and click "Save image"</p>
          </div>
          <div id="container">
      			<div id="generated_qr_code"></div>
      		</div>
        </div>
      </div>
    </div>
  </div>
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
		titleFont: "bold 24px Arial",
		titleColor: "#fff",
		titleBackgroundColor: "#000094",
		titleHeight: 70,
		titleTop: 60,

		// Subtitle (VolunteerNexus)
		subTitle: "VolunteerNexus",
		subTitleFont: "18px Arial",
		subTitleColor: "#ffffff",
		subTitleTop: 40,

		// QR Code Formatting
		width: 280,
		height: 280,
		colorDark: "#000000",
		colorLight: "#ffffff",

		// QR Code Quiet Zone Formatting
    quietZone: 40,
    quietZoneColor: "#ffffff",

		// Logo
		logo: "../images/logo.png",
		logoWidth: 80,
		logoHeight: 80,
		logoBackgroundColor: '#ffffff', // Invalid when `logBgTransparent` is true; default is '#ffffff'
		logoBackgroundTransparent: false, // When transparent image is used, default is false

		// Correction Level
		correctLevel: QRCode.CorrectLevel.H // L, M, Q, H
	};

	// Generate QR Code
	new QRCode(document.getElementById("generated_qr_code"), config);
</script>
