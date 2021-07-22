<?php
require '../classes/VolunteerAffiliationReader.php';
$obj = new VolunteerAffiliationReader($_SESSION['volunteer_id']);
$sponsors = $obj->getAffiliatedSponsors();
?>
<html>
<body>
    <div class="row my-4">
        <div class="col-12 col-xl-12 mb-4 mb-lg-0">
            <div class="card">
                <h5 class="card-header">My contributions</h5>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">Sponsor</th>
                                <th scope="col">Semester</th>
                                <th scope="col">School&nbsp;Year</th>
                                <th scope="col">All&nbsp;Time</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($sponsors as $sponsor): ?>
                                <tr>
                                    <th scope="row"><?php echo $sponsor['sponsor_name']; ?></th>
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
                                        <div class="btn-group">
                                            <a href=<?php echo "affiliation-read.php?sponsor_id=".$sponsor['sponsor_id']; ?> class='btn btn-sm btn-primary' >View</a>
                                            <a href=<?php echo "affiliation-delete.php?affiliation_id=". $sponsor['affiliation_id']; ?> class='btn btn-sm btn-danger'>Leave</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <a href="#" class="btn btn-block btn-light card-footer">Join a sponsor</a>

            </div>
        </div>
    </div>
</body>
</html>