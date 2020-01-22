<?php
require 'DatabaseConnection.php';

class SponsorAffiliationReader {
	protected $pdo = null;
	private $sponsor_id;

	public function __construct($sponsor_id)
	{
		$this->pdo = (new DatabaseConnection)->getPDO();
		$this->sponsor_id = $sponsor_id;
	}

	public function getAffiliatedVolunteers(): ?array
	{
		$sql =
			"SELECT
				volunteers.volunteer_id AS volunteer_id,
				volunteers.last_name AS last_name,
				volunteers.first_name AS first_name,
				volunteers.username AS email_address,
				SUM(engagements.contribution_value) AS total_contribution_value
			FROM
				volunteers
				LEFT JOIN
					engagements
					ON volunteers.volunteer_id = engagements.volunteer_id
			WHERE
				engagements.sponsor_id = :sponsor_id
			GROUP BY
				volunteers.last_name,
				volunteers.first_name,
				volunteers.username";

			$stmt = $this->pdo->prepare($sql);
			$volunteers = $stmt->execute(['sponsor_id' => $this->sponsor_id])->fetch(PDO::FETCH_ASSOC);

		if(!$volunteers) {
			return null;
		}
		else {
			return $volunteers;
		}
	}

	public function getAffiliatedVolunteerDetails($volunteer_id): ?array
	{
		$sql =
		"SELECT
			engagements.contribution_value AS contribution_value,
			engagements.status AS status,
			events.event_name AS event_name,
			events.description AS event_description,
			events.contact_name AS contact_name,
			events.contact_email AS contact_email,
			events.event_start AS event_start,
			events.event_end AS event_end,
			opportunities.opportunity_name AS opportunity_name
		FROM
			engagements
			INNER JOIN
				events
				ON engagements.event_id = events.event_id
			INNER JOIN
				opportunities
				ON engagements.opportunity_id = opportunities.opportunity_id
		WHERE
			engagements.volunteer_id = :volunteer_id
			AND engagements.sponsor_id = :sponsor_id";
	}
}
?>
