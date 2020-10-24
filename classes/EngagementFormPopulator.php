<?php
require_once 'DatabaseConnection.php';

class EngagementFormPopulator {
	protected $pdo = null;
	private $sponsor_id;

	public function __construct($sponsor_id)
	{
		$this->pdo = DatabaseConnection::instance();
		$this->sponsor_id = $sponsor_id;
	}

	public function getVolunteers(): ?string
	{
		$sql = 
			"SELECT DISTINCT 
				volunteers.volunteer_id AS volunteer_id, 
				volunteers.last_name AS last_name, 
				volunteers.first_name AS first_name
			FROM 
				volunteers
				INNER JOIN 
				affiliations 
				ON affiliations.volunteer_id = volunteers.volunteer_id
			WHERE 
				affiliations.sponsor_id = :sponsor_id
			ORDER BY 
				volunteers.last_name 
				ASC";
		
		$stmt = $this->pdo->prepare($sql);
		$status = $stmt->execute(['sponsor_id' => $this->sponsor_id]);
		if(!$status) {
			return null;
		}
		else {
			$volunteers = array();
			$volunteers[] = array("volunteer_name" => 'Select Name', "volunteer_id" => '');
			foreach ($stmt as $row)
			{
				$full_name = $row['last_name'] . ", " . $row['first_name'];
				$volunteers[] = array("volunteer_name" => $full_name, "volunteer_id" => $row['volunteer_id']);
			}
			$jsonVolunteers = json_encode($volunteers);
			return $jsonVolunteers;
		}
	}

	public function getEvents(): ?string
	{
		$sql = 
			"SELECT 
				event_id, 
				event_name 
			FROM 
				events 
			WHERE 
                sponsor_id = :sponsor_id
            ORDER BY event_id DESC";

		$stmt = $this->pdo->prepare($sql);
		$status = $stmt->execute(['sponsor_id' => $this->sponsor_id]);
		if(!$status) {
			return null;
		}
		else {
			$events = array();
			$events[] = array("event_name" => 'Select Event', "event_id" => '');
			foreach ($stmt as $row)
			{
				$events[] = array("event_name" => $row['event_name'], "event_id" => $row['event_id']);
			}
			$jsonEvents = json_encode($events);
			return $jsonEvents;
		}
	}

	public function getOpportunities(): ?string
	{
		$sql = 
			"SELECT 
				opportunity_id, 
				contribution_value, 
				event_id, 
				opportunity_name
			FROM 
				opportunities
			WHERE 
				sponsor_id = :sponsor_id
			ORDER BY 
				start_date 
				DESC";
		
		$stmt = $this->pdo->prepare($sql);
		$status = $stmt->execute(['sponsor_id' => $this->sponsor_id]);
		if(!$status) {
			return null;
		}
		else {
			$opportunities = array();
			foreach ($stmt as $row)
			{
				$opportunities[$row['event_id']][] = array("opportunity_name" => $row['opportunity_name'], "opportunity_id" => $row['opportunity_id'], "contribution_value" => $row['contribution_value']);
			}
			$jsonOpportunities = json_encode($opportunities);
			return $jsonOpportunities;
		}
	}
}
?>
