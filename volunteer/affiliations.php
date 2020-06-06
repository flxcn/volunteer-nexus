<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Affiliations</title>
    <style type="text/css">
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
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">My Affiliations</h2>
                        <a href="affiliation-create.php" class="btn btn-primary pull-right">Add New Affiliation</a>
                    </div>

                    <?php
                    require '../classes/VolunteerAffiliationReader.php';
                    $obj = new VolunteerAffiliationReader($_SESSION['volunteer_id']);
                    $sponsors = $obj->getAffiliatedSponsors();
                    ?>
                    
                    <?php if ($sponsors): ?>
                        <table class='table table-bordered'>
                            <thead>
                                <tr>
                                    <th>Affiliated Sponsor</th>
                                    <th>My Total Contributions</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                            <?php foreach($sponsors as $sponsor): ?>
                                <tr>
                                    <td>
                                        <?php echo $sponsor['sponsor_name']; ?>
                                    </td>
                                    <td>
                                        <?php echo $sponsor['total_contribution_value']; ?>
                                    </td>
                                    <td>
                                        <?php echo $sponsor['total_contribution_value']; ?>
                                    </td>
                                    <td>
                                        <a href=<?php echo "affiliation-read.php?sponsor_id=".$sponsor['sponsor_id']; ?> title='View My Contributions' data-toggle='tooltip' class='btn btn-link' ><span class='glyphicon glyphicon-eye-open'></span> View</a>
                                        <a href=<?php echo "affiliation-delete.php?affiliation_id=". $row['affiliation_id']; ?> title='Delete This Affiliation' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                      <p class='lead'><em>As you participate in opportunities, your progress will automatically appear here.</em></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery, ensures one-time use of submit button -->
    <script>
    $("body").on("submit", "form", function() {
        $(this).submit(function() {
            return false;
        });
        return true;
    });
    </script>
</body>
</html>
