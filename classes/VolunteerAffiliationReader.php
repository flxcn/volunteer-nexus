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

    public function getSemesterContributionTotal($sponsor_id): float
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
            'volunteer_id' => $this->volunteer_id,
            'sponsor_id' => $sponsor_id,
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

    public function getSchoolYearContributionTotal($sponsor_id): float
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
            'volunteer_id' => $this->volunteer_id,
            'sponsor_id' => $sponsor_id,
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
				e2.event_name AS event_name,
				e2.description AS event_description,
				o.opportunity_name AS opportunity_name,
				e2.contact_name AS contact_name,
				e2.contact_email AS contact_email,
				e2.event_start AS event_start,
				e2.event_end AS event_end,
				e1.contribution_value AS contribution_value,
				e1.status AS status
			FROM
				engagements AS e1
				INNER JOIN
					events AS e2
					ON e1.event_id = e2.event_id
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

    public function getAffiliatedSponsorName($sponsor_id) {
        $sql =
            "SELECT
                sponsor_name
            FROM
                sponsors
            WHERE 
                sponsor_id = :sponsor_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['sponsor_id' => $sponsor_id]);
        $sponsor_name = $stmt->fetchColumn();

        if($sponsor_name) {
            return " for " . $sponsor_name;
        }
        else {
            return "";
        }
    }
}
?>
