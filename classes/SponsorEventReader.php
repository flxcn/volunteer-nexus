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

    public function setEventId($event_id) {
        $this->event_id = $event_id;
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

    public function countSponsoredEvents(): int
	{
		$sql =
			"SELECT
				COUNT(*)
			FROM
				events
			WHERE
				sponsor_id = :sponsor_id";

			$stmt = $this->pdo->prepare($sql);
			$stmt->execute(['sponsor_id' => $this->sponsor_id]);
			$count = $stmt->fetchColumn();

		return $count;
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

    public function countUpcomingSponsoredEvents(): int
	{
		$sql =
			"SELECT
				COUNT(*)
			FROM
				events
			WHERE
                sponsor_id = :sponsor_id
                AND event_start > CURDATE()";

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute(['sponsor_id' => $this->sponsor_id]);
		$count = $stmt->fetchColumn();

		return $count;
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
    
    public function countOngoingSponsoredEvents(): int
	{
		$sql =
			"SELECT
				COUNT(*)
			FROM
				events
			WHERE
                sponsor_id = :sponsor_id
                AND event_start <= CURDATE()
                AND event_end >= CURDATE()";

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute(['sponsor_id' => $this->sponsor_id]);
		$count = $stmt->fetchColumn();

		return $count;
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

    public function countCompletedSponsoredEvents(): int
	{
		$sql =
			"SELECT
				COUNT(*)
			FROM
				events
			WHERE
                sponsor_id = :sponsor_id
                AND event_end < CURDATE()";

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute(['sponsor_id' => $this->sponsor_id]);
		$count = $stmt->fetchColumn();

		return $count;
    }

    public function formatEventTable($events): ?string
	{
		if(!$events) {
			return "<p class='lead'><em>No events were found.</em></p>";
		}
        else {
			$formatted_table = "<table class='table table-condensed print' id='events'>";
                $formatted_table .= "<thead>";
                    $formatted_table .= "<tr>";
                        $formatted_table .= "<th style='cursor:pointer'>Reg. Deadline</th>";
                        $formatted_table .= "<th onclick='sortTable(1)' style='cursor:pointer'>Event Name</th>";
                        $formatted_table .= "<th>Description</th>";
                        $formatted_table .= "<th>Location</th>";
                        $formatted_table .= "<th>Duration</th>";
                        $formatted_table .= "<th>Action</th>";
                    $formatted_table .= "</tr>";
                $formatted_table .= "</thead>";

                $formatted_table .= "<tbody id='eventTableBody'>";
                foreach($events as $event) 
                {
                    $formatted_table .= "<tr>";
                        $formatted_table .= "<td class='text-nowrap'>" . $this->formatDate($event['registration_end']) . "</td>";
                        $formatted_table .= "<td>" . $event['event_name'] . "</td>";
                        $formatted_table .= "<td>" . $this->formatDescription($event['description']) . "</td>";
                        $formatted_table .= "<td>" . $event['location'] . "</td>";
                        $formatted_table .= "<td class='text-nowrap'>" . $this->formatEventStartToEnd($event['event_start'],$event['event_end']) . "</td>";
                        $formatted_table .= "<td>";
                            $formatted_table .= "<a href='event-read.php?event_id=". $event['event_id'] ."' title='View Event' data-toggle='tooltip' class='btn btn-link'><span class='glyphicon glyphicon-eye-open'></span> View</a>";
                            $formatted_table .= "<br>";
                            $formatted_table .= "<a href='event-update.php?event_id=". $event['event_id'] ."' title='Update Event' data-toggle='tooltip' class='btn btn-link' style='color:black'><span class='glyphicon glyphicon-pencil'></span> Update</a>";
                            $formatted_table .= "<br>";
                            $formatted_table .= "<a href='event-delete.php?event_id=". $event['event_id'] ."' title='Delete Event' data-toggle='tooltip' class='btn btn-link' style='color:red'><span class='glyphicon glyphicon-trash'></span> Delete</a>";
                        $formatted_table .= "</td>";
                    $formatted_table .= "</tr>";
                }
                $formatted_table .= "</tbody>";
			$formatted_table .= "</table>";

			return $formatted_table;
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
			return $date1 . "<br>to<br>" . $date2;
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

    public function getIsPublic(): string
    {
        if($this->is_public) {
            return "Public Event";
        } else {
            return "Private Event";
        }
    }

    public function formatLinks($text) 
    {
        return preg_replace('@(http)?(s)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@', '<a target="ref" href="http$2://$4">$1$2$3$4</a>', $text);
    }
}
?>
