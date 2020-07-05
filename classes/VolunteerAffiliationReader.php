<?php
require 'DatabaseConnection.php';

class VolunteerAffiliationReader {
	protected $pdo = null;
	private $volunteer_id;

	public function __construct($volunteer_id)
	{
		$this->pdo = DatabaseConnection::instance();
		$this->volunteer_id = $volunteer_id;
	}

	public function getAffiliatedSponsors(): ?array
	{
		$sql =
			"SELECT 
				a.affiliation_id,
				a.sponsor_id, 
				s.sponsor_name,
				a.volunteer_id, 
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
						sponsor_id, 
						volunteer_id
				) AS e 
				ON a.sponsor_id = e.sponsor_id 
				AND a.volunteer_id = e.volunteer_id 
			INNER JOIN 
				sponsors AS s
				ON a.sponsor_id = s.sponsor_id
			WHERE 
				a.volunteer_id = :volunteer_id";

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute(['volunteer_id' => $this->volunteer_id]);
		$affiliated_sponsors = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if(!$affiliated_sponsors) {
			return null;
		}
		else {
			return $affiliated_sponsors;
		}
	}

	public function getEngagementsForAffiliatedSponsor($sponsor_id): ?array
	{
		$sql =
			"SELECT
				events.event_name AS event_name,
				events.description AS event_description,
				o.opportunity_name AS opportunity_name
				events.contact_name AS contact_name,
				events.contact_email AS contact_email,
				events.event_start AS event_start,
				events.event_end AS event_end,
				e1.contribution_value AS contribution_value,
				e1.status AS status,
			FROM
				engagements AS e1
				INNER JOIN
					events AS e2
					ON e1.event_id = events.event_id
				INNER JOIN
					opportunities AS o
					ON e1.opportunity_id = o.opportunity_id
			WHERE
				e1.volunteer_id = :volunteer_id
				AND e1.sponsor_id = :sponsor_id";

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute(['volunteer_id' => $this->volunteer_id, 'sponsor_id' => $sponsor_id]);
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
