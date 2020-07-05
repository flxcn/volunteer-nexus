<?php
require_once 'DatabaseConnection.php';

class EngagementVerification {
	protected $pdo = null;
	private $sponsor_id;
	private $engagement_id;
	private $engagement_status;

	public function __construct(int $sponsor_id)
	{
		$this->pdo = DatabaseConnection::instance();
		$this->sponsor_id = $sponsor_id;
		$this->engagement_id = "";
		$this->engagement_status = null;
	}

	public function setEngagementId(string $engagement_id): bool
	{
		if(empty($engagement_id)) {
			return false;
		}
		else {
			$this->engagement_id = $engagement_id;
			return true;
		}
	}

	public function setEngagementStatus(int $engagement_status): bool
	{
		if($engagement_status == -1) {
			$this->engagement_status = null;
			return true;
		}
		elseif($engagement_status == 0){
			$this->engagement_status = 0;
			return true;
		}
		elseif($engagement_status == 1){
			$this->engagement_status = 1;
			return true;
		}
		else {
			return false;
		}
	}

	public function updateEngagement(): bool {
		$sql =
			"UPDATE
				engagements
			SET
				status = :engagement_status
			WHERE
				sponsor_id = :sponsor_id
				AND engagement_id = :engagement_id";
		$stmt = $this->pdo->prepare($sql);
		$status = $stmt->execute(
			[
				'engagement_status' => $this->engagement_status,
				'sponsor_id' => $this->sponsor_id,
				'engagement_id' => $this->engagement_id
			]);
		return $status;
	}
}
?>
