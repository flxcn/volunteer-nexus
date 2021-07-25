<?php
require_once 'DatabaseConnection.php';

class AdminDashboard {
	protected $pdo = null;

	public function __construct()
	{
		$this->pdo = DatabaseConnection::instance();
	}

    public function countVolunteers(): int
	{
		$sql =
			"SELECT
				COUNT(*)
			FROM
				volunteers";

			$stmt = $this->pdo->prepare($sql);
			$stmt->execute();
			$count = $stmt->fetchColumn();

		return $count;
    }

    public function calculateIncreaseInVolunteers(): int
	{
		$sql =
			"SELECT
				COUNT(*)
			FROM
				volunteers
            WHERE 
                time_created ";

			$stmt = $this->pdo->prepare($sql);
			$stmt->execute();
			$count = $stmt->fetchColumn();

		return $count;
    }

    public function countEngagements(): int
	{
		$sql =
			"SELECT
				COUNT(*)
			FROM
				engagements";

			$stmt = $this->pdo->prepare($sql);
			$stmt->execute();
			$count = $stmt->fetchColumn();

		return $count;
    }

    public function countSponsors(): int
	{
		$sql =
			"SELECT
				COUNT(*)
			FROM
				sponsors";

			$stmt = $this->pdo->prepare($sql);
			$stmt->execute();
			$count = $stmt->fetchColumn();

		return $count;
    }

    public function countOpportunities(): int
	{
		$sql =
			"SELECT
				COUNT(*)
			FROM
				opportunities";

			$stmt = $this->pdo->prepare($sql);
			$stmt->execute();
			$count = $stmt->fetchColumn();

		return $count;
    }

    public function formatDisplayNumber($number) {
        if($number < 1000) {
            return $number;
        } else {
            $number / 1000;
            return round($number/1000, 1, PHP_ROUND_HALF_UP) . "k";
        }
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
		$sql = "SELECT event_id, event_name, sponsor_name, description, contact_name, event_start, event_end FROM events";
		$stmt = $this->pdo->prepare($sql);
		$status = $stmt->execute();
		if(!$status) {
			return null;
		}
		else {
			$events = array();
			foreach ($stmt as $row)
			{
				$events[] = array("event_name" => $row['event_name'], "event_id" => $row['event_id']);
			}
			$jsonEvents = json_encode($events);
			return $jsonEvents;
		}
	}

    public function getLatestEvents(): ?string
	{
		$sql = "SELECT event_id, event_name FROM events WHERE sponsor_id = :sponsor_id LIMIT 5";
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
}
?>
