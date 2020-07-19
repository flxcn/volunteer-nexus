<?php
require_once 'DatabaseConnection.php';

class SponsorEngagementReader {
	protected $pdo = null;
	private $sponsor_id;

	public function __construct($sponsor_id)
	{
		$this->pdo = DatabaseConnection::instance();
		$this->sponsor_id = $sponsor_id;
	}

	public function getPendingCompletedEngagements(): ?array
	{
        $sql = 
            "SELECT 
                opportunities.start_date AS start_date, 
                engagements.engagement_id AS engagement_id, 
                engagements.time_submitted, 
                volunteers.first_name AS first_name, 
                volunteers.last_name AS last_name, 
                events.event_name AS event_name, 
                opportunities.opportunity_name AS opportunity_name
            FROM 
                engagements
            LEFT JOIN 
                volunteers
                ON volunteers.volunteer_id = engagements.volunteer_id 
            LEFT JOIN 
                events
                ON events.event_id = engagements.event_id 
            LEFT JOIN 
                opportunities 
                ON opportunities.opportunity_id = engagements.opportunity_id
            WHERE 
                engagements.sponsor_id = :sponsor_id
                AND engagements.status IS NULL 
                AND opportunities.start_date <= CURDATE()
            GROUP BY 
                engagements.time_submitted, 
                engagements.engagement_id, 
                events.event_name, 
                opportunities.opportunity_name, 
                volunteers.first_name, 
                volunteers.last_name";

			$stmt = $this->pdo->prepare($sql);
			$stmt->execute(['sponsor_id' => $this->sponsor_id]);
			$engagements = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if(!$engagements) {
			return null;
		}
		else {
			return $engagements;
		}
    }
    
    public function getPendingEngagements(): ?array
	{
        $sql = 
            "SELECT 
                opportunities.start_date AS start_date, 
                engagements.engagement_id AS engagement_id, 
                engagements.time_submitted, 
                volunteers.first_name AS first_name, 
                volunteers.last_name AS last_name, 
                events.event_name AS event_name, 
                opportunities.opportunity_name AS opportunity_name
            FROM 
                engagements
            LEFT JOIN 
                volunteers
                ON volunteers.volunteer_id = engagements.volunteer_id 
            LEFT JOIN 
                events
                ON events.event_id = engagements.event_id 
            LEFT JOIN 
                opportunities 
                ON opportunities.opportunity_id = engagements.opportunity_id
            WHERE 
                engagements.sponsor_id = :sponsor_id
                AND engagements.status IS NULL 
            GROUP BY 
                engagements.time_submitted, 
                engagements.engagement_id, 
                events.event_name, 
                opportunities.opportunity_name, 
                volunteers.first_name, 
                volunteers.last_name";

			$stmt = $this->pdo->prepare($sql);
			$stmt->execute(['sponsor_id' => $this->sponsor_id]);
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
