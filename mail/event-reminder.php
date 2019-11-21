<?php
require_once 'config.php';
require 'mailer.php';

// SQL query to filter tables to only return information about volunteers engaging in opportunities that will happen the next day
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
  opportunities.needs_reminder = 1 AND
  DATEDIFF(opportunities.start_date, CURDATE()) = 1;
";


if($result = mysqli_query($link, $sql)) {
    if(mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_array($result)) {

              // set recipient name
							$recipientName	= $row['first_name'] . " " . $row['last_name'];

              // set recipient email address
							$recipientEmail = $row['email_address'];

              // set subject
							$messageSubject	= "Notice: New Opportunity Added! " . $row['opportunity_name'] . " ";

              // set HTML message body
              $messageBody =
              "
              <body>
                <h2>Hello " . $row['first_name'] . ",</h2>
                <p>This is a friendly reminder that the <b>" . $row['opportunity_name'] . "</b> opportunity, part of the " . "<b>" . $row['event_name']."</b>" . " event, is happening tomorrow! Here are some key details to keep you informed:</p>

                <table>
                  <tr>
                    <td><b>Start Date & Time:</b></td>
                  	<td>" . $row['start_date'] . " " . $row['start_time'] . "</td>
                  </tr>
                  <tr>
                    <td><b>End Date & Time:</b></td>
                  	<td>" . $row['end_date'] . " " . $row['end_time'] . "</td>
                  </tr>
                  <tr>
                  </tr>
                  <tr>
                  </tr>
                  <tr>
                    <td><b>Sponsor Name:</b></td>
                  	<td>" . $row['sponsor_name'] . "</td>
                  </tr>
                  <tr>
                    <td><b>Location:</b></td>
                  	<td>" . $row['location'] . "</td>
                  </tr>
                  <tr>
                    <td><b>Description:</b></td>
                  	<td>" . $row['description'] . "</td>
                  </tr>
                  <tr>
                  </tr>
                  <tr>
                  </tr>
                  <tr>
                    <td><b>Contact Name:</b></td>
                  	<td>" . $row['contact_name'] . "</td>
                  </tr>
                  <tr>
                    <td><b>Contact Email:</b></td>
                  	<td>" . $row['contact_email'] . "</td>
                  </tr>
                  <tr>
                    <td><b>Contact Phone:</b></td>
                  	<td>" . $row['contact_phone'] . "</td>
                  </tr>
                  <tr>
                  </tr>
                </table>

                <p>Best Regards,<br>Felix from VolunteerNexus</p>

              </body>
              ";

              // set text only email body
							$messageAltBody =
              "
              Hello " . $row['first_name'] . ",
              \n
              \nThis is a friendly reminder that the " . $row['opportunity_name'] . " opportunity, in the " . $row['event_name'] . " is happening tomorrow! Here are some key details to keep you informed:
              \n
              \n
              \nStart Date & Time: " . $row['start_date'] . " " . $row['start_time'] . "
              \nEnd Date & Time:" . $row['end_date'] . " " . $row['end_time'] . "
              \n
              \nSponsor Name: " . $row['sponsor_name'] . "
              \nEvent Name: " . $row['event_name'] . "
              \nLocation: " . $row['location'] . "
              \nDescription: " . $row['description'] . "
              \n
              \nContact Name: " . $row['contact_name'] . "
              \nContact Email: " . $row['contact_email'] . "
              \nContact Phone:" . $row['contact_phone'] . "
              ";

              // call sendMessage function, found in mailer.php
              sendMessage($recipientName, $recipientEmail, $messageSubject, $messageBody, $messageAltBody);
            }

        mysqli_free_result($result);
    }
} else {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}
mysqli_close($link);
?>
