<?php
require_once 'DatabaseConnection.php';

class SponsorAffiliation {
	protected $pdo = null;
	private $sponsor_id;
	private $volunteer_id;

	public function __construct($sponsor_id)
	{
		$this->pdo = DatabaseConnection::instance();
		$this->sponsor_id = $sponsor_id;
		$this->volunteer_id = "";
	}

	public function setVolunteerId(string $volunteer_id): string
	{
		if(empty($volunteer_id)) {
			return "Please select a Volunteer.";
		}
		else {
			$this->volunteer_id = $volunteer_id;
			return "";
		}
	}

	public function addAffiliation(): bool
	{
		$sql =
			"INSERT INTO
				affiliations (volunteer_id, sponsor_id)
			VALUES
				(:volunteer_id, :sponsor_id)";
		$stmt = $this->pdo->prepare($sql);
		$status = $stmt->execute(['volunteer_id' => $this->volunteer_id, 'sponsor_id' => $this->sponsor_id]);
		return $status;
	}

	public function checkAffiliationExists(): bool
	{
		$sql =
			"SELECT
				affiliation_id
			FROM
				affiliations
			WHERE
				volunteer_id = :volunteer_id
				AND sponsor_id = :sponsor_id";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute(['volunteer_id' => $this->volunteer_id, 'sponsor_id' => $this->sponsor_id]);
		$affiliation_id = $stmt->fetchColumn();

		if($affiliation_id) {
			return true;
		}
		else {
			return false;
		}
	}

	public function removeAffiliation($affiliation_id): bool 
	{
		$sql =
			"DELETE FROM 
				affiliations
			WHERE
				sponsor_id = :sponsor_id
				AND affiliation_id = :affiliation_id";
		$stmt = $this->pdo->prepare($sql);
		$status = $stmt->execute(['sponsor_id' => $this->sponsor_id, 'affiliation_id' => $affiliation_id]);
		return $status;
	}


}
?>
