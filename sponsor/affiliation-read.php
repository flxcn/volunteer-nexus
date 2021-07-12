<?php
session_start();

if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
    header("location: login.php");
    exit;
}

require "../classes/SponsorAffiliationReader.php";

$obj = new SponsorAffiliationReader($_SESSION["sponsor_id"]);
$engagements = $obj->getEngagementsForAffiliatedVolunteer($_GET['volunteer_id']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>This Volunteer's Contributions</title>
    <!--Load required libraries-->
    <?php include '../head.php'?>
    <style type="text/css">
        .wrapper{
            margin: 0 auto;
        }
        .page-header h2{
            margin-top: 0;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</head>

<body>
    <?php $thisPage='Affiliations'; include 'navbar.php';?>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Volunteer's Past Engagements for This Sponsor</h2>
                        <a href='affiliations.php' class="btn btn-primary pull-right">Back</a>
                    </div>

                    <?php if ($engagements): ?>
                        <table class='table table-bordered table-striped' id='engagements'>
                            <thead>
                                <tr>
                                    <th>Event Name</th>
                                    <th>Event Description</th>
                                    <th>Opportunity Name</th>
                                    <th>Contact Info</th>
                                    <th>Event Duration</th>
                                    <th>Value</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                            <?php foreach($engagements as $engagement): ?>
                                <tr>
                                    <td>
                                        <?php echo $engagement['event_name']; ?>
                                    </td>
                                    <td>
                                        <?php echo $engagement['event_description']; ?>
                                    </td>
                                    <td>
                                        <?php echo $engagement['opportunity_name']; ?>
                                    </td>
                                    <td>
                                        <?php echo $engagement['contact_name'] . "<br>" . $engagement['contact_email']; ?>
                                    </td>
                                    <td>
                                        <?php echo $engagement['event_start'] . " to " . $engagement['event_end']; ?>
                                    </td>
                                    <td>
                                        <?php echo $engagement['contribution_value']; ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (strcmp($engagement['status'],'1') == 0):
                                            echo "Confirmed";
                                        elseif (strcmp($engagement['status'],'0') == 0):
                                            echo "Denied";
                                        else:
                                            echo "Pending";
                                        endif;
                                        ?>
                                    </td>   
                                    <td>
                                        <a href=<?php echo "engagement-delete.php?opportunity_id=".$engagement['opportunity_id']."&engagement_id=". $engagement['engagement_id']; ?> title='Delete Engagement' data-toggle='tooltip' style='color:red' class='btn btn-link' ><span class='glyphicon glyphicon-trash'></span> Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class='lead'><em>No past engagements were found.</em></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php include '../footer.php';?>
</body>
</html>
