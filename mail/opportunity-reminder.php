<?php
require_once '../classes/OpportunityReminder.php';
require 'mailer.php';

$obj = new OpportunityReminder();

// SQL query to filter tables to only return information about volunteers engaging in opportunities that will happen the next day
$volunteers = $obj->getVolunteersWhoNeedReminders();

if($volunteers) {
    foreach($volunteers as $volunteer) {
        // set recipient name
        $recipientName	= $volunteer['first_name'] . " " . $volunteer['last_name'];

        // set recipient email address
        $recipientEmail = $volunteer['email_address'];

        // set subject
        $messageSubject	= "Don't forget! " . $volunteer['opportunity_name'] . " - Tomorrow!";

        // set HTML message body
        $messageBody =
            "<body>
                <h2>Hello " . $volunteer['first_name'] . ",</h2>
                <p>This is a friendly reminder that the <b>" . $volunteer['opportunity_name'] . "</b> opportunity, part of the " . "<b>" . $volunteer['event_name']."</b>" . " event, is happening tomorrow! Here are some key details to keep you informed:</p>

                <table>
                    <tr>
                        <td><b>Start Date & Time:</b></td>
                        <td>" . $volunteer['start_date'] . " " . $volunteer['start_time'] . "</td>
                    </tr>
                    <tr>
                        <td><b>End Date & Time:</b></td>
                        <td>" . $volunteer['end_date'] . " " . $volunteer['end_time'] . "</td>
                    </tr>

                    <tr></tr>
                    <tr></tr>

                    <tr>
                        <td><b>Sponsor Name:</b></td>
                        <td>" . $volunteer['sponsor_name'] . "</td>
                    </tr>
                    <tr>
                        <td><b>Location:</b></td>
                        <td>" . $volunteer['location'] . "</td>
                    </tr>
                    <tr>
                        <td><b>Description:</b></td>
                        <td>" . $volunteer['description'] . "</td>
                    </tr>

                    <tr></tr>
                    <tr></tr>

                    <tr>
                        <td><b>Contact Name:</b></td>
                        <td>" . $volunteer['contact_name'] . "</td>
                    </tr>
                    <tr>
                        <td><b>Contact Email:</b></td>
                        <td>" . $volunteer['contact_email'] . "</td>
                    </tr>
                    <tr>
                        <td><b>Contact Phone:</b></td>
                        <td>" . $volunteer['contact_phone'] . "</td>
                    </tr>

                    <tr></tr>
                </table>

                <p>Best Regards,<br>Felix from VolunteerNexus</p>

            </body>";

        // set text only email body
        $messageAltBody =
            "Hello " . $volunteer['first_name'] . ",
            \n
            \nThis is a friendly reminder that the " . $volunteer['opportunity_name'] . " opportunity, in the " . $volunteer['event_name'] . " is happening tomorrow! Here are some key details to keep you informed:
            \n
            \n
            \nStart Date & Time: " . $volunteer['start_date'] . " " . $volunteer['start_time'] . "
            \nEnd Date & Time:" . $volunteer['end_date'] . " " . $volunteer['end_time'] . "
            \n
            \nSponsor Name: " . $volunteer['sponsor_name'] . "
            \nEvent Name: " . $volunteer['event_name'] . "
            \nLocation: " . $volunteer['location'] . "
            \nDescription: " . $volunteer['description'] . "
            \n
            \nContact Name: " . $volunteer['contact_name'] . "
            \nContact Email: " . $volunteer['contact_email'] . "
            \nContact Phone:" . $volunteer['contact_phone'] . "";

        // call sendMessage function, found in mailer.php
        sendMessage($recipientName, $recipientEmail, $messageSubject, $messageBody, $messageAltBody);
    }
}
?>