<?php
require_once 'DatabaseConnection.php';

class SponsorOpportunityCreation
{
    protected $pdo = null;
	private $opportunity_id;
    private $event_id;
	private $sponsor_id;
	private $opportunity_name;
    private $description;
    private $start_date;
    private $end_date;
    private $start_time;
    private $end_time;
    private $total_positions;
    private $limit_per_volunteer;
	private $contribution_value;
	private $needs_verification;
    private $needs_reminder;

	public function __construct(int $sponsor_id)
    {
        $this->pdo = DatabaseConnection::instance();
        $this->opportunity_id = "";
        $this->event_id = 0;
		$this->sponsor_id = $sponsor_id;
		$this->opportunity_name = "";
        $this->description = "";
        $this->start_date = "";
        $this->end_date = "";
        $this->start_time = "";
        $this->end_time = "";
        $this->total_positions = "";
        $this->limit_per_volunteer = 1;
		$this->contribution_value = 0.0;
        $this->needs_verification = "";
		$this->needs_reminder = "";
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

	public function setOpportunityName(string $opportunity_name): string
	{
		if(empty($opportunity_name)) {
			return "Please enter an opportunity name.";
		}
		else {
			$this->opportunity_name = $opportunity_name;
			return "";
		}
	}

    public function setDescription(string $description): string
	{
		if(empty($description)) {
			return "Please enter a description.";
		}
		else {
			$this->description = $description;
			return "";
		}
	}

    public function setStartDate(string $start_date): string
	{
		if(empty($start_date)) {
			return "Please enter a start date.";
		}
		else {
			$this->start_date = $start_date;
			return "";
		}
	}

    public function setEndDate(string $end_date): string
	{
		if(empty($end_date)) {
			return "Please enter an end date.";
		}
		else {
			$this->end_date = $end_date;
			return "";
		}
	}

    public function setStartTime(string $start_time): string
	{
		if(empty($start_time)) {
			return "Please enter a start time.";
		}
		else {
			$this->start_time = date("H:i", strtotime($start_time));
			return "";
		}
	}

    public function setEndTime(string $end_time): string
	{
		if(empty($end_time)) {
			return "Please enter an end time.";
		}
		else {
			$this->end_time = date("H:i", strtotime($end_time));
			return "";
		}
	}

    public function setTotalPositions(string $total_positions): string
	{
		if(empty($total_positions)) {
            $this->total_positions = null;
            return "";
		}
		else if(is_numeric($total_positions)) {
			$this->total_positions = $total_positions;
			return "";
		}
        else {
            return "Not a valid number";
        }
	}

    public function setLimitPerVolunteer(string $limit_per_volunteer): string
	{
		if(empty($limit_per_volunteer)) {
			return "Please enter the total number of sign-ups each volunteer is allowed.";
		}
		else {
			$this->limit_per_volunteer = $limit_per_volunteer;
			return "";
		}
	}

    public function setContributionValue($contribution_value): string
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

	public function setNeedsReminder($needs_reminder): string
	{
		$this->needs_reminder = $needs_reminder;
		return "";
	}

    public function setNeedsVerification($needs_verification): string
	{
        $this->needs_verification = $needs_verification;
        return "";
	}

    public function addOpportunity(): bool
    {
        $sql = 
            "INSERT INTO opportunities 
                        (event_id, 
                         sponsor_id, 
                         opportunity_name, 
                         description, 
                         start_date, 
                         end_date, 
                         start_time, 
                         end_time, 
                         total_positions, 
                         limit_per_volunteer, 
                         contribution_value, 
                         needs_verification, 
                         needs_reminder) 
            VALUES      (:event_id,
                         :sponsor_id,
                         :opportunity_name,
                         :description, 
                         :start_date, 
                         :end_date, 
                         :start_time, 
                         :end_time, 
                         :total_positions, 
                         :limit_per_volunteer, 
                         :contribution_value, 
                         :needs_verification, 
                         :needs_reminder)";
                
		$stmt = $this->pdo->prepare($sql);
		$status = $stmt->execute(
			[
				'event_id' => $this->event_id,
				'sponsor_id' => $this->sponsor_id,
				'opportunity_name' => $this->opportunity_name,
				'description' => $this->description,
				'start_date' => $this->start_date,
                'end_date' => $this->end_date,
				'start_time' => $this->start_time,
				'end_time' => $this->end_time,
				'total_positions' => $this->total_positions,
                'limit_per_volunteer' => $this->limit_per_volunteer,
				'contribution_value' => $this->contribution_value,
				'needs_verification' => $this->needs_verification,
				'needs_reminder' => $this->needs_reminder
			]);

		return $status;
	}

}
?>
