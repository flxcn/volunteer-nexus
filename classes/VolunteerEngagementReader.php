<?php
require_once 'DatabaseConnection.php';

class VolunteerEngagementReader {
	protected $pdo = null;
	private $volunteer_id;

	public function __construct($volunteer_id)
	{
		$this->pdo = DatabaseConnection::instance();
		$this->volunteer_id = $volunteer_id;
    }

	public function getUpcomingEngagements(): ?array
	{
		$sql =
            "SELECT     engagements.engagement_id       AS engagement_id,
                        engagements.contribution_value  AS contribution_value,
                        engagements.time_submitted      AS time_submitted,
                        opportunities.opportunity_id    AS opportunity_id,
                        opportunities.description       AS description,
                        opportunities.start_date        AS start_date,
                        opportunities.end_date          AS end_date,
                        opportunities.start_time        AS start_time,
                        opportunities.end_time          AS end_time,
                        opportunities.opportunity_name  AS opportunity_name,
                        events.event_name               AS event_name,
                        events.contact_name             AS contact_name,
                        events.contact_email            AS contact_email,
                        events.contribution_type        AS contribution_type
            FROM        engagements
                        LEFT JOIN   volunteers
                               ON   volunteers.volunteer_id = engagements.volunteer_id
                        LEFT JOIN   events
                               ON   events.event_id = engagements.event_id
                        LEFT JOIN   opportunities
                               ON   opportunities.opportunity_id = engagements.opportunity_id
            WHERE       engagements.volunteer_id = :volunteer_id
                        AND opportunities.end_date >= CURDATE()
            GROUP BY    opportunities.start_date,
                        opportunities.end_date,
                        opportunities.start_time,
                        opportunities.end_time,
                        engagements.engagement_id,
                        events.event_name,
                        opportunities.opportunity_name,
                        opportunities.opportunity_id,
                        events.contribution_type,
                        engagements.contribution_value,
                        engagements.time_submitted";

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute(['volunteer_id' => $this->volunteer_id]);
		$engagements = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if(!$engagements) {
			return null;
		}
		else {
			return $engagements;
		}
	}

    public function formatDate($date_string): ?string
	{
		$date = strtotime($date_string);
        // return date('D, M. jS', $date);
        return date('M. jS', $date);
	}

	public function formatOpportunityStartToEnd($event_start,$event_end): ?string
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

    public function formatTime($time_string): ?string
	{
		$time = strtotime($time_string);
        return date('g:i A', $time);
	}
}
?>
