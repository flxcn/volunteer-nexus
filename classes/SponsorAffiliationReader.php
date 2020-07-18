<?php
require_once 'DatabaseConnection.php';

class SponsorAffiliationReader {
	protected $pdo = null;
	private $sponsor_id;

	public function __construct($sponsor_id)
	{
		$this->pdo = DatabaseConnection::instance();
		$this->sponsor_id = $sponsor_id;
	}

	public function getCutoffDate(): int 
	{ 
		$cutoff_date = date('Y');
		//AND IF(MONTH(CURDATE())<=6,v.graduation_year>=YEAR(CURDATE()),v.graduation_year>YEAR(CURDATE())
		if(date('m') > 6)
		{
			$cutoff_date += 1;
		}

		return $cutoff_date;
	}

	public function getAffiliatedVolunteers(): ?array
	{
		$sql =
			"SELECT DISTINCT
				a.affiliation_id,
				a.volunteer_id, 
				v.username AS email_address,
				v.first_name,
				v.last_name,
				v.graduation_year,
				a.sponsor_id, 
				COALESCE(e.total, 0) AS total_contribution_value
			FROM
				affiliations AS a 
			LEFT JOIN 
				(SELECT 
					sponsor_id, 
					volunteer_id, 
					SUM(contribution_value) AS total 
				FROM
					engagements 
					WHERE 
						status = '1'
					GROUP BY 
						volunteer_id, 
						sponsor_id
				) AS e 
				ON a.sponsor_id = e.sponsor_id 
				AND a.volunteer_id = e.volunteer_id 
			INNER JOIN 
				volunteers AS v
				ON a.volunteer_id = v.volunteer_id
			WHERE 
                (a.sponsor_id = :sponsor_id)
                AND (v.graduation_year >= :cutoff_date
                OR v.graduation_year IS NULL)";

			$stmt = $this->pdo->prepare($sql);
			$stmt->execute(['sponsor_id' => $this->sponsor_id, 'cutoff_date' => $this->getCutoffDate()]);
			$volunteers = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
