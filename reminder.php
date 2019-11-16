<?php
require_once 'config.php';
require_once 'mailer.php';

// search through the database to find all rows with the "reminderSent" row set as false;
// from these, futher filter to the only ones with the opportunity start date being tomorrow;
// send a message, using the sendMessage() function

// Run SQL Query
$sql = "SELECT volunteers.first_name AS first_name, volunteers.last_name AS last_name, volunteers.username AS email_address, opportunities.opportunity_name AS total_contribution_value
FROM volunteers INNER JOIN engagements ON volunteers.volunteer_id = engagements.volunteer_id LEFT JOIN opportunities ON affiliations.volunteer_id = engagements.volunteer_id
WHERE engagements.sponsor_id = '{$_SESSION['sponsor_id']}' AND engagements.status = '1'
GROUP BY volunteers.last_name, volunteers.first_name, volunteers.username";

if($result = mysqli_query($link, $sql)) {
    if(mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_array($result)){
							$recipientName	= $row['first_name'] + ' ' + $row['last_name'];
							$recipientEmail = $row['email_address'];
							$messageSubject	= "Don't forget! " + $row['opportunity_name'] + "Tomorrow!";
							$messageBody		= '';
							$messageAltBody = ;
                echo "<tr>";
                    echo "<td>" . $row['last_name'] . ", " . $row['first_name'] . "</td>";
                    echo "<td>" . $row['email_address'] . "</td>";
                    echo "<td>" . $row['total_contribution_value'] . "</td>";

                echo "</tr>";
            }

        mysqli_free_result($result);
    } else
    {
        echo "<p class='lead'><em>No affiliated volunteers were found.</em></p>";
    }
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}
mysqli_close($link);
?>
