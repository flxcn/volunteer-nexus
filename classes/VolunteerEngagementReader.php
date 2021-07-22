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
                        opportunities.opportunity_id    AS opportunity_id,
                        opportunities.start_date        AS start_date,
                        opportunities.end_date          AS end_date,
                        opportunities.start_time        AS start_time,
                        opportunities.end_time          AS end_time,
                        events.event_name               AS event_name,
                        opportunities.opportunity_name  AS opportunity_name
            FROM        engagements
            LEFT JOIN   volunteers
            ON          volunteers.volunteer_id = engagements.volunteer_id
            LEFT JOIN   events
            ON          events.event_id = engagements.event_id
            LEFT JOIN   opportunities
            ON          opportunities.opportunity_id = engagements.opportunity_id
            WHERE       engagements.volunteer_id = :volunteer_id
            AND         opportunities.end_date >= CURDATE()
            GROUP BY    opportunities.start_date,
                        opportunities.end_date,
                        opportunities.start_time,
                        opportunities.end_time,
                        engagements.engagement_id,
                        events.event_name,
                        opportunities.opportunity_name,
                        opportunities.opportunity_id";

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
}
?>
