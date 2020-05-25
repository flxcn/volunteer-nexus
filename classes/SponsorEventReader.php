<?php
require 'DatabaseConnection.php';

class SponsorEventReader {
	protected $pdo = null;
	private $sponsor_id;

	public function __construct($sponsor_id)
	{
		$this->pdo = (new DatabaseConnection)->getPDO();
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
