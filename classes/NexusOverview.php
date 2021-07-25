<?php
require_once 'DatabaseConnection.php';

class NexusOverview {
	protected $pdo = null;

	public function __construct()
	{
		$this->pdo = DatabaseConnection::instance();
	}

    public function countVolunteers(): int
	{
		$sql =
			"SELECT
				COUNT(*)
			FROM
				volunteers";

			$stmt = $this->pdo->prepare($sql);
			$stmt->execute();
			$count = $stmt->fetchColumn();

		return $count;
    }

    public function countEngagements(): int
	{
		$sql =
			"SELECT
				COUNT(*)
			FROM
				engagements";

			$stmt = $this->pdo->prepare($sql);
			$stmt->execute();
			$count = $stmt->fetchColumn();

		return $count;
    }

    public function countSponsors(): int
	{
		$sql =
			"SELECT
				COUNT(*)
			FROM
				sponsors";

			$stmt = $this->pdo->prepare($sql);
			$stmt->execute();
			$count = $stmt->fetchColumn();

		return $count;
    }

    public function countOpportunities(): int
	{
		$sql =
			"SELECT
				COUNT(*)
			FROM
				opportunities";

			$stmt = $this->pdo->prepare($sql);
			$stmt->execute();
			$count = $stmt->fetchColumn();

		return $count;
    }

    public function formatDisplayNumber($number) {
        if($number < 1000) {
            return $number;
        } else {
            $number / 1000;
            return round($number/1000, 1, PHP_ROUND_HALF_UP) . "k";
        }
    }

}
?>
