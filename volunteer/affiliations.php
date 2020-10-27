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
                                    <th rowspan="2">Affiliated Sponsor</th>
                                    <th colspan="3">My Total Contributions</th>
                                    <th rowspan="2">Action</th>
                                </tr>
                                <tr>
                                    <th>This Semester</th>
                                    <th>This School Year</th>
                                    <th>All Time</th>
                                </tr>
                            </thead>

                            <tbody>
                            <?php foreach($sponsors as $sponsor): ?>
                                <tr>
                                    <td>
                                        <?php echo $sponsor['sponsor_name']; ?>
                                    </td>
                                    <td>
                                        <?php echo $obj->getSemesterContributionTotal($sponsor['sponsor_id']); ?>
                                    </td>
                                    <td>
                                        <?php echo $obj->getSchoolYearContributionTotal($sponsor['sponsor_id']); ?>
                                    </td>
                                    <td>
                                        <?php echo $sponsor['total_contribution_value']; ?>
                                    </td>
                                    <td>
                                        <a href=<?php echo "affiliation-read.php?sponsor_id=".$sponsor['sponsor_id']; ?> title='View My Contributions' data-toggle='tooltip' class='btn btn-link' ><span class='glyphicon glyphicon-eye-open'></span> View</a>
                                        <br>
                                        <a href=<?php echo "affiliation-delete.php?affiliation_id=". $sponsor['affiliation_id']; ?> title='Delete This Affiliation' data-toggle='tooltip' class='btn btn-link' style='color:red'><span class='glyphicon glyphicon-trash' style='color:red'></span> Delete</a>
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
</body>
</html>
