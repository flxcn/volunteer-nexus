<?php
require_once 'config.php';

// $host = '127.0.0.1';
// $db   = 'test';
// $user = 'root';
// $pass = '';
// $charset = 'utf8mb4';
//
// $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
// $options = [
//     PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
//     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//     PDO::ATTR_EMULATE_PREPARES   => false,
// ];
// try {
//      $pdo = new PDO($dsn, $user, $pass, $options);
// } catch (\PDOException $e) {
//      throw new \PDOException($e->getMessage(), (int)$e->getCode());
// }

require_once 'mailer.php';

// search through the database to find all rows with the "reminderSent" row set as false;
// from these, futher filter to the only ones with the opportunity start date being tomorrow;
// send a message, using the sendMessage() function

// Run SQL Query
$sql =
"SELECT
  volunteers.first_name AS first_name, volunteers.last_name AS last_name, volunteers.username AS email_address,
  events.event_name AS event_name, events.sponsor_name AS sponsor_name, events.location AS location, events.contact_name AS contact_name, events.contact_phone AS contact_phone, events.contact_email AS contact_email,
  opportunities.role_name AS opportunity_name, opportunities.description AS description, opportunities.start_date AS start_date, opportunities.start_time AS start_time, opportunities.end_date AS end_date, opportunities.end_time AS end_time
FROM engagements
  INNER JOIN volunteers ON volunteers.volunteer_id = engagements.volunteer_id
  INNER JOIN events ON events.event_id = engagements.event_id
  INNER JOIN opportunities ON opportunities.opportunity_id = engagements.opportunity_id
WHERE
  engagements.status != 0 AND
  engagements.reminder_sent = 0 AND
  DATEDIFF(opportunities.start_date, CURDATE()) = 1;
";


if($result = mysqli_query($link, $sql)) {
    if(mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_array($result)) {

              // set recipient name
							$recipientName	= $row['first_name'] + ' ' + $row['last_name'];

              // set recipient email address
							$recipientEmail = $row['email_address'];

              // set subject
							$messageSubject	= "Don't forget! " + $row['opportunity_name'] + " - Tomorrow!";

              // create HTML message body
              $messageBody =
              "
              <body>
                <h1>Hello" + $row['first_name'] + ",</h1>
                <p>This is a friendly reminder that the <b>" + $row['opportunity_name'] + "</b> opportunity, in the " + "<b>" + $row['event_name']+"</b>" + "is happening tomorrow! Here are some key details to keep you informed:</p>

                <table>
                  <tr>
                    <td>Start Date & Time:</td>
                  	<td>" + $row['start_date'] + " " + $row['start_time'] + "</td>
                  </tr>
                  <tr>
                    <td>End Date & Time:</td>
                  	<td>" + $row['end_date'] + " " + $row['end_time'] + "</td>
                  </tr>
                </table>

                <table>
                  <tr>
                    <td>Sponsor Name:</td>
                  	<td>" + $row['sponsor_name'] + "</td>
                  </tr>
                  <tr>
                    <td>Event Name:</td>
                  	<td>" + $row['event_name'] + "</td>
                  </tr>
                  <tr>
                    <td>Location:</td>
                  	<td>" + $row['location'] + "</td>
                  </tr>
                  <tr>
                    <td>Description:</td>
                  	<td>" + $row['description'] + "</td>
                  </tr>
                </table>

                <table>
                  <tr>
                    <td>Contact Name:</td>
                  	<td>" + $row['contact_name'] + "</td>
                  </tr>
                  <tr>
                    <td>Contact Email:</td>
                  	<td>" + $row['contact_email'] + "</td>
                  </tr>
                  <tr>
                    <td>Contact Phone:</td>
                  	<td>" + $row['contact_phone'] + "</td>
                  </tr>
                </table>
              </body>
              ";

              // create text only email body
							$messageAltBody =
              "
              Hello" + $row['first_name'] + ",
              \n
              \nThis is a friendly reminder that the " + $row['opportunity_name'] + " opportunity, in the " + $row['event_name'] + " is happening tomorrow! Here are some key details to keep you informed:
              \n
              \n
              \nStart Date & Time: " + $row['start_date'] + " " + $row['start_time'] + "
              \nEnd Date & Time:" + $row['end_date'] + " " + $row['end_time'] + "
              \n
              \nSponsor Name: " + $row['sponsor_name'] + "
              \nEvent Name: " + $row['event_name'] + "
              \nLocation: " + $row['location'] + "
              \nDescription: " + $row['description'] + "
              \n
              \nContact Name: " + $row['contact_name'] + "
              \nContact Email: " + $row['contact_email'] + "
              \nContact Phone:" + $row['contact_phone'] + "
              ";

              sendMessage($recipientName, $recipientEmail, $messageSubject, $messageBody);
            }

        mysqli_free_result($result);
    }
} else {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}
mysqli_close($link);
?>
