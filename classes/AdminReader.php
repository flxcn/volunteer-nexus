<?php
require_once 'DatabaseConnection.php';

class AdminReader {
	protected $pdo = null;

	public function __construct($admin_id)
	{
		$this->pdo = DatabaseConnection::instance();
		$this->admin_id = $admin_id;
	}

	public function getVolunteers(): ?array
	{
		$sql =
			"SELECT DISTINCT
				volunteers.volunteer_id AS volunteer_id,
				volunteers.last_name AS last_name,
				volunteers.first_name AS first_name,
                volunteers.time_created AS time_created,
                username
		FROM volunteers
		ORDER BY volunteers.volunteer_id DESC";
		$volunteers = $this->pdo->query($sql)->fetchAll(PDO::FETCH_UNIQUE);
		if(!$volunteers) {
			return null;
		}
		else {
			return $volunteers;
		}
	}

	public function getEvents(): ?array
	{
		$sql =
            "SELECT     event_id,
                        event_name, 
                        sponsor_name,
                        description,
                        location,
                        event_start,
                        event_end,
                        registration_start, 
                        registration_end 
            FROM        events";

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute();
		$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if(!$events) {
			return null;
		}
		else {
			return $events;
		}
    }

    public function getLatestEvents(): ?array
	{
		$sql =
            "SELECT     event_id,
                        event_name, 
                        sponsor_name,
                        description,
                        location,
                        event_start,
                        event_end,
                        registration_start, 
                        registration_end 
            FROM        events
            ORDER BY    event_id DESC
            LIMIT 5";

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute();
		$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if(!$events) {
			return null;
		}
		else {
			return $events;
		}
    }

    public function getLatestEngagements(): ?array
    {
        $sql =
            "SELECT     engagement_id,
                        volunteer_id,
                        sponsor_id, 
                        event_id,
                        contribution_value,
                        status,
                        time_submitted
            FROM        engagements
            ORDER BY    engagement_id DESC
            LIMIT 50";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $engagements = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(!$engagements) {
            return null;
        }
        else {
            return $engagements;
        }
    }

    public function getSponsors(): ?array
	{
		$sql = "SELECT sponsor_name, username FROM sponsors";
		$stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $sponsors = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if(!$sponsors) {
			return null;
		}
		else {
            return $sponsors;
		}
	}

    public function formatDescription($description): ?string
	{
		if(strlen($description)>100){
			$truncated = substr($description, 0, 99);
	    return $truncated . "...";
		}
		else {
			return $description;
		}
	}

    public function formatDate($date_string): ?string
	{
		$date = strtotime($date_string);
        // return date('D, M. jS', $date);
        return date('M. jS', $date);
	}

    public function formatEventStartToEnd($event_start,$event_end): ?string
	{
		$date1 = $this->formatDate($event_start);
		if (strcmp($event_start,$event_end) == 0) {
			return $date1;
		}
		else {
			$date2 = $this->formatDate($event_end);
			return $date1 . " to " . $date2;
		}
	}
}
?>
