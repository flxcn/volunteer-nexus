<?php
require_once 'DatabaseConnection.php';

class VolunteerAffiliationReader {
	protected $pdo = null;
	private $volunteer_id;

	public function __construct($volunteer_id)
	{
		$this->pdo = DatabaseConnection::instance();
		$this->volunteer_id = $volunteer_id;
    }

    public function getCurrentSemesterDateRange(): array {

        $start_date = date("Y-m-d");
        $end_date = date("Y-m-d");

        // check if today's date is past June 15th
        if(date('m-d') >= "06-15")
		{
            // first semester
            $start_date = date("Y") . "-06-15";
            $end_date = date("Y") . "-12-31";	
        }
        else
		{
            // second semester
			$start_date = date("Y") . "-01-01";
            $end_date = date("Y") . "-06-14";
        }

        return array(
            "start_date" => $start_date,
            "end_date" => $end_date
        );
    }
    
    public function getSemesterContributionTotal($sponsor_id): int 
	{ 
        $semester_date_ranges = getCurrentSemesterDateRange();

        $sql =
			"SELECT  
				COALESCE(e.total, 0) AS total_semester_contribution_value
			FROM
                engagements AS e1
                INNER JOIN
                    events AS e2
                    ON e2.event_id = e1.event_id
            WHERE 
                e1.status = '1'
                AND e1.sponsor_id = :sponsor_id
                AND e1.volunteer_id = :volunteer_id
                AND e2.event_end >= :start_date_range
                AND e2.event_end <= :end_date_range";

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([
            'volunteer_id' => $this->volunteer_id, 
            'sponsor_id' => $this->volunteer_id, 
            'start_date_range' => $semester_date_ranges["start_date"],
            'end_date_range' => $semester_date_ranges["end_date"],
        ]);
		$semester_total = $stmt->fetch();

		if(!$semester_total) {
			return null;
		}
		else {
			return $semester_total;
        }
    }
    
    public function getCurrentSchoolYearDateRange(): array {

        $start_date = date("Y-m-d");
        $end_date = date("Y-m-d");

        // check if today's date is past June 15th
        if(date('m-d') >= "06-15")
		{
            // first semester
            $start_date = date("Y") . "-06-15";
            $end_date = date("Y") . "-12-31";	
        }
        else
		{
            // second semester
			$start_date = date("Y") . "-01-01";
            $end_date = date("Y") . "-06-14";
        }

        return array(
            "start_date" => $start_date,
            "end_date" => $end_date
        );
    }
    public function getSchoolYearContributionTotal($sponsor_id): int 
	{ 
        $semester_date_ranges = getCurrentSemesterDateRange();

        $sql =
			"SELECT  
				COALESCE(e.total, 0) AS total_semester_contribution_value
			FROM
                engagements AS e1
                INNER JOIN
                    events AS e2
                    ON e2.event_id = e1.event_id
            WHERE 
                e1.status = '1'
                AND e1.sponsor_id = :sponsor_id
                AND e1.volunteer_id = :volunteer_id
                AND e2.event_end >= :start_date_range
                AND e2.event_end <= :end_date_range";

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([
            'volunteer_id' => $this->volunteer_id, 
            'sponsor_id' => $this->volunteer_id, 
            'start_date_range' => $semester_date_ranges["start_date"],
            'end_date_range' => $semester_date_ranges["end_date"],
        ]);
		$semester_total = $stmt->fetch();

		if(!$semester_total) {
			return null;
		}
		else {
			return $semester_total;
        }
    }

	public function getAffiliatedSponsors(): ?array
	{
		$sql =
			"SELECT 
				a.affiliation_id,
				a.sponsor_id AS sponsor_id, 
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
