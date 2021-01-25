<?php
require_once 'DatabaseConnection.php';

class VolunteerEventReader {
	protected $pdo = null;
	private $volunteer_id;

	public function __construct($volunteer_id)
	{
		$this->pdo = DatabaseConnection::instance();
		$this->volunteer_id = $volunteer_id;
	}

	public function getEvents(): ?array
	{
		$sql =
            "SELECT 
                event_id,
                event_name, 
                sponsor_name,
                description,
                location,
                event_start,
                event_end,
                registration_start, 
                registration_end 
            FROM events
                INNER JOIN affiliations 
                    ON affiliations.sponsor_id = events.sponsor_id
                WHERE registration_start <= CURDATE()
                AND registration_end >= CURDATE()
                AND affiliations.volunteer_id = :volunteer_id
            UNION
            SELECT 
                event_id,
                event_name, 
                sponsor_name,
                description,
                location,
                event_start,
                event_end,
                registration_start, 
                registration_end  
            FROM events
            WHERE events.is_public = 1
                AND registration_start <= CURDATE()
                AND registration_end >= CURDATE()
            ORDER BY registration_end";

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute(['volunteer_id' => $this->volunteer_id]);
		$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if(!$events) {
			return null;
		}
		else {
			return $events;
		}
    }

    public function countEvents(): int
	{
        $sql =
            "SELECT COUNT(*)
            FROM events
                INNER JOIN affiliations 
                    ON affiliations.sponsor_id = events.sponsor_id
                WHERE registration_start <= CURDATE()
                AND registration_end >= CURDATE()
                AND affiliations.volunteer_id = :volunteer_id
            UNION
            SELECT COUNT(*)  
            FROM events
            WHERE events.is_public = 1
                AND registration_start <= CURDATE()
                AND registration_end >= CURDATE()
            ORDER BY registration_end";

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute(['volunteer_id' => $this->volunteer_id]);
		$count = $stmt->fetchColumn();

		return $count;
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
			return $date1 . "<br>to<br>" . $date2;
		}
	}

}
?>
