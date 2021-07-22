<!DOCTYPE html>
<html lang="en">
<head>
    <script src="../assets/EasyQRCodeJS/easy.qrcode.js" type="text/javascript" charset="utf-8"></script>
</head>
<body>
    <p>Get this Volunteer ID scanned to track your attendance at opportunities.</p>
    <div id="container">
        <div id="generated_qr_code"></div>
    </div>

    <div class="card-footer">
        <a href="#" id="downloadLink" onclick="getLink()" class="my-3 py-2 btn btn-primary" download="volunteernexus-id">Download</a>
    </div>

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
            logo: "../assets/images/logo.png",
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
    <script type="text/javascript">
        function getLink() {
            var imageSource = document.querySelectorAll('#generated_qr_code img')[0].src
            console.log(imageSource);
            document.getElementById("downloadLink").href = imageSource;
        }
    </script>
</body>
</html>


