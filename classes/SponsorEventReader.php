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

    public function formatEventTable($events): ?string
	{
		if(!$events) {
			return "<p class='lead'><em>No events were found.</em></p>";
		}
    else {
			$formatted_table = "<table class='table table-condensed' id='events'>";
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
			foreach($events as $event) {
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
			return $date1 . " to " . $date2;
		}
	}
}
?>
