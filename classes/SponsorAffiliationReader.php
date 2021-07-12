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
				a.volunteer_id AS volunteer_id, 
				v.username AS email_address,
				v.first_name,
				v.last_name,
				v.graduation_year AS graduation_year,
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
                OR v.graduation_year IS NULL
                OR v.graduation_year = '0')";

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

    public function getEngagementsForAffiliatedVolunteer(int $volunteer_id) {
        $sql =
            "SELECT
                engagements.engagement_id AS engagement_id,
                engagements.contribution_value AS contribution_value,
                engagements.status AS status,
                events.event_name AS event_name,
                events.description AS event_description,
                events.contact_name AS contact_name,
                events.contact_email AS contact_email,
                events.event_start AS event_start,
                events.event_end AS event_end,
                opportunities.opportunity_name AS opportunity_name,
                opportunities.opportunity_id AS opportunity_id
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

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['sponsor_id' => $this->sponsor_id, 'volunteer_id' => $volunteer_id]);
        $engagements = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(!$engagements) {
			return null;
		}
		else {
			return $engagements;
		}
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

    public function getSemesterContributionTotal($volunteer_id): float
	{
        $semester_date_ranges = $this->getCurrentSemesterDateRange();

        $sql =
			"SELECT
				COALESCE(SUM(e1.contribution_value),0) AS total_semester_contribution_value
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
            'volunteer_id' => $volunteer_id,
            'sponsor_id' => $this->sponsor_id,
            'start_date_range' => $semester_date_ranges["start_date"],
            'end_date_range' => $semester_date_ranges["end_date"],
        ]);
		$semester_total = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!$semester_total) {
			return null;
		}
		else {
			return $semester_total["total_semester_contribution_value"];
        }
    }

	public function getCurrentSchoolYearDateRange(): array {

        $start_date = date("Y-m-d");
        $end_date = date("Y-m-d");

        // check if today's date is past June 15th
        if(date('m-d') >= "06-15")
		{
            // in first semester, so includes next year
            $start_date = date("Y") . "-06-15";
            $end_date = (date("Y")+1) . "-06-14";
        }
        else
		{
            // in second semester, so includes last year
			$start_date = (date("Y")-1) . "-06-15";
            $end_date = date("Y") . "-06-14";
        }

        return array(
            "start_date" => $start_date,
            "end_date" => $end_date
        );
    }

    public function getSchoolYearContributionTotal($volunteer_id): float
	{
        $school_year_date_ranges = $this->getCurrentSchoolYearDateRange();

        $sql =
			"SELECT
				COALESCE(SUM(e1.contribution_value),0) AS total_school_year_contribution_value
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
            'volunteer_id' => $volunteer_id,
            'sponsor_id' => $this->sponsor_id,
            'start_date_range' => $school_year_date_ranges["start_date"],
            'end_date_range' => $school_year_date_ranges["end_date"],
        ]);
		$school_year_total = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!$school_year_total) {
			return null;
		}
		else {
			return $school_year_total["total_school_year_contribution_value"];
        }
    }
}
?>
