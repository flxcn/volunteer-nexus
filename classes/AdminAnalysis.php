<?php
require 'DatabaseConnection.php';

class AdminAnalysis {
	protected $pdo = null;

	public function __construct($sponsor_id)
	{
		$this->pdo = (new DatabaseConnection)->getPDO();
		$this->sponsor_id = $sponsor_id;
	}

	public function getAllVolunteers(): ?string
	{
		$sql =
			"SELECT DISTINCT
				volunteers.volunteer_id AS volunteer_id,
				volunteers.last_name AS last_name,
				volunteers.first_name AS first_name
		FROM volunteers
		ORDER BY volunteers.volunteer_id ASC";
		$volunteers = $this->pdo->query($sql)->fetchAll(PDO::FETCH_UNIQUE);
		if(!$volunteers) {
			return null;
		}
		else {
			return $volunteers;
		}
	}

	public function getEvents(): ?string
	{
		$sql = "SELECT event_id, event_name FROM events WHERE sponsor_id = :sponsor_id";
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

	public function getContributionTotalsBySponsor(): ?array
	{
		$data = $pdo->query('SELECT affiliations.sponsor_id AS sponsor_id, engagements.volunteer_id AS volunteer_id, engagements.contribution_value AS contribution_value FROM engagements INNER JOIN affiliations ON affiliations.volunteer_id = engagements.volunteer_id')
		->fetchAll(PDO::FETCH_GROUP);

		$contributionTotalsBySponsor = new array();
		for($sponsor=0; $sponsor<count($data); $sponsor++) {
			$contributionTotalsBySponsor[] = new array();
			foreach($data[$sponsor] as $engagement)
			{
				if(array_key_exists($engagement['volunteer_id'],$contributionTotalsBySponsor[$sponsor]))
				{
					$contributionTotalsBySponsor[$engagement['volunteer_id']] += $engagement['contribution_value'];
				}
				else
				{
					$contributionTotalsBySponsor[$sponsor][$engagement['volunteer_id']] = $engagement['contribution_value'];
				}
			}
		}

		return $contributionTotalsBySponsor;
	}

}
?>
