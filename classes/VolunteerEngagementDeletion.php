<?php
require_once 'DatabaseConnection.php';

class VolunteerEngagementDeletion {
	protected $pdo = null;
	private $volunteer_id;

	public function __construct($volunteer_id)
	{
		$this->pdo = DatabaseConnection::instance();
		$this->volunteer_id = $volunteer_id;
    }

    public function removeEngagement($engagement_id): bool 
	{
		$sql =
			"DELETE FROM 
				engagements
			WHERE
				volunteer_id = :volunteer_id
				AND engagement_id = :engagement_id";
		$stmt = $this->pdo->prepare($sql);
		$status = $stmt->execute(['volunteer_id' => $this->volunteer_id, 'engagement_id' => $engagement_id]);
		return $status;
	}
}
?>
