<?php
require_once 'DatabaseConnection.php';

class VolunteerEngagementCreation
{
  protected $pdo = null;
	private $volunteer_id;
	private $sponsor_id;
	private $event_id;
	private $opportunity_id;
	private $contribution_value;
	private $status;

    public function __construct($volunteer_id) 
    {
        $this->pdo = DatabaseConnection::instance();
        $this->volunteer_id = $volunteer_id;
        $this->sponsor_id = 0;
        $this->event_id = 0;
        $this->opportunity_id = 0;
        $this->contribution_value = 0.0;
        $this->status = NULL;
    }

	public function setSponsorId(int $sponsor_id): string
	{
		if(empty($sponsor_id)) {
			return "Please select a sponsor.";
		}
		else {
			$this->sponsor_id = $sponsor_id;
			return "";
		}
	}

	public function setEventId(int $event_id): string
	{
		if(empty($event_id)) {
			return "Please select an event.";
		}
		else {
			$this->event_id = $event_id;
			return "";
		}
	}

	public function setOpportunityId(int $opportunity_id): string
	{
		if(empty($opportunity_id)) {
			return "Please select an opportunity.";
		}
		else {
			$this->opportunity_id = $opportunity_id;
			return "";
		}
	}

	public function setContributionValue(float $contribution_value): string
	{
		if(empty($contribution_value)) {
			$this->contribution_value = 0.0;
			return "";
		}
		else {
			$this->contribution_value = $contribution_value;
			return "";
		}
	}

	public function setStatus(int $status): string
	{
		$this->status = $status;
		return "";
	}

    public function addEngagement(): bool
    {
		$sql = "INSERT INTO engagements (volunteer_id, event_id, opportunity_id, sponsor_id, contribution_value, status)
			VALUES (:volunteer_id, :event_id, :opportunity_id, :sponsor_id, :contribution_value, :status)";
		$stmt = $this->pdo->prepare($sql);
		$status = $stmt->execute(
			[
				'volunteer_id' => $this->volunteer_id,
				'event_id' => $this->event_id,
				'opportunity_id' => $this->opportunity_id,
				'sponsor_id' => $this->sponsor_id,
				'contribution_value' => $this->contribution_value,
				'status' => $this->status
			]);

		return $status;
	}

	public function countExistingDuplicateEngagements(): ?int
	{
		// find corresponding volunteer_id to input student_id
        $sql = 
            "SELECT COUNT(*) 
            FROM engagements 
            WHERE volunteer_id = :volunteer_id 
                AND event_id = :event_id 
                AND opportunity_id = :opportunity_id";
		$stmt = $this->pdo->prepare($sql);
		$status = $stmt->execute(['volunteer_id' => $this->volunteer_id, 'event_id' => $this->event_id, 'opportunity_id' => $this->opportunity_id]);
		$current_count = $stmt->fetchColumn();

		if($current_count) {
			return $current_count;
		}
		else {
			return null;
		}
    }
    
    public function getLimitPerVolunteer(): ?int
	{
		// find corresponding volunteer_id to input student_id
        $sql = 
            "SELECT limit_per_volunteer
            FROM opportunities 
            WHERE opportunity_id = :opportunity_id";
		$stmt = $this->pdo->prepare($sql);
		$status = $stmt->execute(['opportunity_id' => $opportunity_id]);
		$limit_per_volunteer = $stmt->fetchColumn();

		if($limit_per_volunteer) {
			return $limit_per_volunteer;
		}
		else {
			return null;
		}
	}

	public function isLimitPerVolunteerReached(): bool
	{
        $current_count = $this->countExistingDuplicateEngagements();
		$limit_per_volunteer_of_engagement = $this->getLimitPerVolunteer();

        if($limit_per_volunteer_of_engagement == null || $current_count == null) {
            return false;
        }
        else if($current_count >= $limit_per_volunteer_of_engagement) {
            return true;
        }
        else {
            return false;
        }
	}
}
?>
