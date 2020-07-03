<?php
require_once 'DatabaseConnection.php';

class EngagementCreation
{
    protected $pdo = null;
	private $volunteer_id;
	private $sponsor_id;
	private $event_id;
	private $opportunity_id;
	private $contribution_value;
	private $status;

	public function __construct($sponsor_id)
  {
    $this->pdo = DatabaseConnection::instance();
    $this->volunteer_id = 0;
		$this->sponsor_id = $sponsor_id;
		$this->event_id = 0;
		$this->opportunity_id = 0;
		$this->contribution_value = 0;
		$this->status = NULL;
	}

	public function setVolunteerId(int $volunteer_id): string
	{
		if(empty($volunteer_id)) {
			return "Please select a volunteer.";
		}
		else {
			$this->$volunteer_id = $volunteer_id;
			return "";
		}
	}

	public function setEventId(int $event_id): string
	{
		if(empty($event_id)) {
			return "Please select an event.";
		}
		else {
			$this->$event_id = $event_id;
			return "";
		}
	}

	public function setOpportunityId(int $opportunity_id): string
	{
		if(empty($opportunity_id)) {
			return "Please select an opportunity.";
		}
		else {
			$this->$opportunity_id = $opportunity_id;
			return "";
		}
	}

	public function setContributionValue(float $contribution_value): string
	{
		if(empty($contribution_value)) {
			$this->$contribution_value = 0.0;
			return "";
		}
		else {
			$this->$contribution_value = $contribution_value;
			return "";
		}
	}

	public function setStatus(int $status): string
	{
		$this->$status = $status;
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

	public function setVolunteerIdByStudentId(int $student_id): bool
	{
		// find corresponding volunteer_id to input student_id
		$sql = "SELECT volunteer_id FROM volunteers WHERE student_id = :student_id";
		$stmt = $this->pdo->prepare($sql);
		$status = $stmt->execute(['student_id' => $student_id]);
		$volunteer_id = $stmt->fetchColumn();

		if($volunteer_id) {
			$this->volunteer_id = $volunteer_id;
  			return true;
		}
		else {
			return false;
		}
	}

	public function addEngagementsByStudentId(array $student_ids): string
	{
		$output = "";
		
		// find corresponding volunteer_ids to all student_ids
		// loop through INSERT sql statement to add all of the engagements with the volunteer_ids
		// for any 
		return $output;
	}

	public function addEngagementsByVolunteerIds(): string
	{
		return "";
    }

    public function getVolunteerId(): string
	{
		return $this->volunteer_id;
	}
}
?>
