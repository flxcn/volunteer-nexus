<?php
require 'DatabaseConnection.php';

class VolunteerAffiliation {
	protected $pdo = null;
	private $volunteer_id;
	private $sponsor_id;

	public function __construct($volunteer_id)
	{
		$this->pdo = (new DatabaseConnection)->getPDO();
		$this->volunteer_id = $volunteer_id;
		$this->sponsor_id = "";
	}

	public function setSponsorId(string $sponsor_id): string
	{
		if(empty($sponsor_id)) {
			return "Please select a Sponsor.";
		}
		else {
			$this->sponsor_id = $sponsor_id;
			return "";
		}
	}

	public function getSponsors(): ?string
	{
		$sql =
		"SELECT DISTINCT
			sponsor_id,
			sponsor_name
		FROM
			sponsors
		ORDER BY
			sponsor_name
			ASC";
		$stmt = $this->pdo->query($sql);
		if(!$stmt) {
			return null;
		}
		else {
			$sponsors = array();
			$sponsors[] = array("sponsor_name" => 'Select Sponsor', "sponsor_id" => '');
			foreach ($stmt as $row)
			{
				$sponsors[] = array("sponsor_name" => $row['sponsor_name'], "sponsor_id" => $row['sponsor_id']);
			}
			$jsonSponsors = json_encode($sponsors);
			return $jsonSponsors;
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
		$stmt->execute([
			'volunteer_id' => $this->volunteer_id,
			'sponsor_id' => $this->sponsor_id]);
		$affiliation_id = $stmt->fetchColumn();

		if($affiliation_id) {
			return true;
		}
		else {
			return false;
		}
	}


}
?>
