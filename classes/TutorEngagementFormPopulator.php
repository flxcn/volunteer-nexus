<?php
require_once 'DatabaseConnection.php';

class TutorEngagementFormPopulator {
	protected $pdo = null;
	private $volunteer_id;

	public function __construct($volunteer_id)
	{
		$this->pdo = DatabaseConnection::instance();
		$this->volunteer_id = $volunteer_id;
	}

    public function getSponsors(): ?string
	{
		$sql = 
			"SELECT DISTINCT 
				sponsors.sponsor_id AS sponsor_id, 
				sponsors.sponsor_name AS sponsor_name
			FROM 
				sponsors
				INNER JOIN 
				affiliations
				ON affiliations.sponsor_id = sponsors.sponsor_id
			WHERE 
				affiliations.volunteer_id = :volunteer_id
			ORDER BY 
				sponsors.sponsor_id 
				ASC";
		
		$stmt = $this->pdo->prepare($sql);
		$status = $stmt->execute(['volunteer_id' => $this->volunteer_id]);
		if(!$status) {
			return null;
		}
		else {
			$sponsors = array();
			$sponsors[] = array("sponsor_name" => 'Select Sponsor', "sponsor_id" => '');
			foreach ($stmt as $row)
			{
				$sponsors[] = array("sponsor_name" => $row['sponsor_name'], "sponsor_id" => $row['sponsor_id']);
			}
			$jsonSponsors = json_encode($sponsors);
			return $jsonSponsors;
		}
	}

	// public function getEvents(): ?string
	// {
	// 	$sql = 
	// 		"SELECT 
	// 			event_id, 
	// 			event_name 
	// 		FROM 
	// 			events 
	// 		WHERE 
	// 			sponsor_id = :sponsor_id";

	// 	$stmt = $this->pdo->prepare($sql);
	// 	$status = $stmt->execute(['sponsor_id' => $this->sponsor_id]);
	// 	if(!$status) {
	// 		return null;
	// 	}
	// 	else {
	// 		$events = array();
	// 		$events[] = array("event_name" => 'Select Event', "event_id" => '');
	// 		foreach ($stmt as $row)
	// 		{
	// 			$events[] = array("event_name" => $row['event_name'], "event_id" => $row['event_id']);
	// 		}
	// 		$jsonEvents = json_encode($events);
	// 		return $jsonEvents;
	// 	}
	// }

	// public function getOpportunities(): ?string
	// {
	// 	$sql = 
	// 		"SELECT 
	// 			opportunity_id, 
	// 			contribution_value, 
	// 			event_id, 
	// 			opportunity_name
	// 		FROM 
	// 			opportunities
	// 		WHERE 
	// 			sponsor_id = :sponsor_id
	// 		ORDER BY 
	// 			start_date 
	// 			DESC";
		
	// 	$stmt = $this->pdo->prepare($sql);
	// 	$status = $stmt->execute(['sponsor_id' => $this->sponsor_id]);
	// 	if(!$status) {
	// 		return null;
	// 	}
	// 	else {
	// 		$opportunities = array();
	// 		foreach ($stmt as $row)
	// 		{
	// 			$opportunities[$row['event_id']][] = array("opportunity_name" => $row['opportunity_name'], "opportunity_id" => $row['opportunity_id'], "contribution_value" => $row['contribution_value']);
	// 		}
	// 		$jsonOpportunities = json_encode($opportunities);
	// 		return $jsonOpportunities;
	// 	}
	// }
}
?>
