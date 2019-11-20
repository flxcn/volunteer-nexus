<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
/* Exception class. */
require 'PHPMailer/src/Exception.php';
/* The main PHPMailer class. */
require 'PHPMailer/src/PHPMailer.php';
/* SMTP class, needed if you want to use SMTP. */
require 'PHPMailer/src/SMTP.php';

//require '/home/customer/www/volunteernexus.com/public_html/PHPMailer/src/Exception.php';
//require '/home/customer/www/volunteernexus.com/public_html/PHPMailer/src/PHPMailer.php';
//require '/home/customer/www/volunteernexus.com/public_html/PHPMailer/src/SMTP.php';

//use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\src\Exception;
//use PHPMailer\PHPMailer\SMTP;




function sendMessage($recipientName, $recipientEmail, $messageSubject, $messageBody, $messageAltBody)
{
	// Instantiation and passing `true` enables exceptions
	$mail = new PHPMailer(true);
	try {
	    // Server settings
	    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
	    $mail->isSMTP();                                            // Send using SMTP
	    $mail->Host       = 'mail.volunteernexus.com';                    // Set the SMTP server to send through
	    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
	    $mail->Username   = 'reminder@volunteernexus.com';                     // SMTP username
	    $mail->Password   = 'VirginiaRometty';                               // SMTP password
	    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
	    $mail->Port       = 25;                                    // TCP port to connect to

	    // Recipients
	    $mail->setFrom('reminder@volunteernexus.com', 'Reminders from VolunteerNexus');
	    $mail->addAddress($recipientEmail, $recipientName);     // Add a recipient
	  	//$mail->addAddress('ellen@example.com');               // Name is optional
	    $mail->addReplyTo('felix@volunteernexus.com', 'Felix from VolunteerNexus');
	    //$mail->addCC('cc@example.com');
	    //$mail->addBCC('bcc@example.com');

	    // Attachments
	    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
	  	// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

	    // Content
	    $mail->isHTML(true);                                  // Set email format to HTML
	    $mail->Subject = $messageSubject;
	    $mail->Body    = $messageBody;
	    $mail->AltBody = $messageAltBody;

	    $mail->send();
	    echo 'Message has been sent';
	} catch (Exception $e) {
	    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	}
}
?>
