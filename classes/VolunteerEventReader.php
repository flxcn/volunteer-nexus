<?php
require_once 'DatabaseConnection.php';

class VolunteerEventReader {
	protected $pdo = null;
	private $volunteer_id;

	public function __construct($volunteer_id)
	{
		$this->pdo = DatabaseConnection::instance();
		$this->volunteer_id = $volunteer_id;
        $this->event_id = "";
        $this->sponsor_id = "";
        $this->event_name = "";
        $this->sponsor_name = "";
        $this->description = "";
        $this->location = "";
        $this->contribution_type = "";
        $this->contact_name = "";
        $this->contact_phone = "";
        $this->contact_email = "";
        $this->registration_start = "";
        $this->registration_end = "";
        $this->event_start = "";
        $this->event_end = "";
        $this->is_public = "";

	}

    public function setEventId($event_id) {
        $this->event_id = $event_id;
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
            FROM        events
            INNER JOIN  affiliations 
            ON          affiliations.sponsor_id = events.sponsor_id
            WHERE       registration_start <= CURDATE()
            AND         registration_end >= CURDATE()
            AND         affiliations.volunteer_id = :volunteer_id
            UNION
            SELECT      event_id,
                        event_name, 
                        sponsor_name,
                        description,
                        location,
                        event_start,
                        event_end,
                        registration_start, 
                        registration_end  
            FROM        events
            WHERE       events.is_public = 1
            AND         registration_start <= CURDATE()
            AND         registration_end >= CURDATE()
            ORDER BY    registration_end";

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

    public function formatLinks($text) 
    {
        return preg_replace('@(http)?(s)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@', '<a target="ref" href="http$2://$4">$1$2$3$4</a>', $text);
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

    public function getEventDetails(): bool
	{
		$sql =
            "SELECT *
            FROM        events
            WHERE       event_id = :event_id";

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute(['event_id' => $this->event_id]);
		$event = $stmt->fetch();

		if($event) {
            $this->event_id = $event["event_id"];
            $this->sponsor_id = $event["sponsor_id"];
            $this->event_name = $event["event_name"];
            $this->sponsor_name = $event["sponsor_name"];
            $this->description = $event["description"];
            $this->location = $event["location"];
            $this->contribution_type = $event["contribution_type"];
            $this->contact_name = $event["contact_name"];
            $this->contact_phone = $event["contact_phone"];
            $this->contact_email = $event["contact_email"];
            $this->registration_start = $event["registration_start"];
            $this->registration_end = $event["registration_end"];
            $this->event_start = $event["event_start"];
            $this->event_end = $event["event_end"];
            $this->is_public = $event["is_public"];
            return true;
        }
		else {
			return false;
		}
    }

    public function getSponsorId(): string
    {
        return $this->sponsor_id;
    }

    public function getEventName(): string
    {
        return $this->event_name;
    }

    public function getSponsorName(): string
    {
        return $this->sponsor_name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getContributionType(): string
    {
        return $this->contribution_type;
    }

    public function getContactName(): string
    {
        return $this->contact_name;
    }

    public function getContactPhone(): string
    {
        return $this->contact_phone;
    }

    public function getContactEmail(): string
    {
        return $this->contact_email;
    }

    public function getRegistrationStart(): string
    {
        return $this->registration_start;
    }

    public function getRegistrationEnd(): string
    {
        return $this->registration_end;
    }

    public function getEventStart(): string
    {
        return $this->event_start;
    }

    public function getEventEnd(): string
    {
        return $this->event_end;
    }

    public function getIsPublic(): bool
    {
        return $this->is_public;
    }



}
?>
