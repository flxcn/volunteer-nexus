<?php
require_once 'DatabaseConnection.php';

class OpportunityReminder {
	protected $pdo = null;

	public function __construct()
	{
		$this->pdo = DatabaseConnection::instance();
	}

	public function getVolunteersWhoNeedReminders(): ?array
	{
		$sql =
            "SELECT volunteers.first_name       AS first_name, 
                    volunteers.last_name        AS last_name, 
                    volunteers.username         AS email_address,

                    events.event_name           AS event_name, 
                    events.sponsor_name         AS sponsor_name, 
                    events.location             AS location, 
                    events.contact_name         AS contact_name, 
                    events.contact_phone        AS contact_phone, 
                    events.contact_email        AS contact_email,

                    opportunities.role_name     AS opportunity_name, 
                    opportunities.description   AS description, 
                    opportunities.start_date    AS start_date, 
                    opportunities.start_time    AS start_time, 
                    opportunities.end_date      AS end_date, 
                    opportunities.end_time      AS end_time
            FROM    engagements
                    INNER JOIN volunteers 
                            ON volunteers.volunteer_id = engagements.volunteer_id
                    INNER JOIN events 
                            ON events.event_id = engagements.event_id
                    INNER JOIN opportunities 
                            ON opportunities.opportunity_id = engagements.opportunity_id
            WHERE   engagements.status != 0 
                    AND opportunities.needs_reminder = 1 
                    AND DATEDIFF(opportunities.start_date, CURDATE()) = 1";

		$stmt = $this->pdo->query($sql);
		$volunteers = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if(!$volunteers) {
			return null;
		}
		else {
			return $volunteers;
		}
    }
}
?>
