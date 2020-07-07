<?php
require_once 'DatabaseConnection.php';

class SponsorEventReader {
	protected $pdo = null;
	private $sponsor_id;

	public function __construct($sponsor_id)
	{
		$this->pdo = DatabaseConnection::instance();
		$this->sponsor_id = $sponsor_id;
	}

	public function getSponsoredEvents(): ?array
	{
		$sql =
			"SELECT
				*
			FROM
				events
			WHERE
				sponsor_id = :sponsor_id
			ORDER BY
				registration_end
				DESC";

			$stmt = $this->pdo->prepare($sql);
			$stmt->execute(['sponsor_id' => $this->sponsor_id]);
			$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if(!$events) {
			return null;
		}
		else {
			return $events;
		}
    }

    public function getUpcomingSponsoredEvents(): ?array
	{
		$sql =
			"SELECT
				*
			FROM
				events
			WHERE
				sponsor_id = :sponsor_id
        AND event_start > CURDATE()
			ORDER BY
				registration_end
				DESC";

			$stmt = $this->pdo->prepare($sql);
			$stmt->execute(['sponsor_id' => $this->sponsor_id]);
			$upcoming_events = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if(!$upcoming_events) {
			return null;
		}
		else {
			return $upcoming_events;
		}
    }

    public function getOngoingSponsoredEvents(): ?array
	{
		$sql =
			"SELECT
				*
			FROM
				events
			WHERE
				sponsor_id = :sponsor_id
          AND event_start <= CURDATE()
          AND event_end >= CURDATE()
			ORDER BY
				registration_end
				DESC";

			$stmt = $this->pdo->prepare($sql);
			$stmt->execute(['sponsor_id' => $this->sponsor_id]);
			$ongoing_events = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if(!$ongoing_events) {
			return null;
		}
		else {
			return $ongoing_events;
		}
	}

    public function getCompletedSponsoredEvents(): ?array
	{
		$sql =
			"SELECT
				*
			FROM
				events
			WHERE
				sponsor_id = :sponsor_id
          AND event_end < CURDATE()
			ORDER BY
				registration_end
				DESC";

			$stmt = $this->pdo->prepare($sql);
			$stmt->execute(['sponsor_id' => $this->sponsor_id]);
			$completed_events = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if(!$completed_events) {
			return null;
		}
		else {
			return $completed_events;
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
    return date('D, M. jS', $date);
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
