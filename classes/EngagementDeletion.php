<?php
class EngagementDeletion
{
    protected $pdo = null;
		private $engagement_id;
		private $sponsor_id;

		public function __construct(int $engagement_id, int $sponsor_id)
    {
      $options = [
			    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			    PDO::ATTR_EMULATE_PREPARES   => false,
			];
      $this->pdo = new PDO("mysql:host=localhost;dbname=volunteer_nexus;charset=utf8mb4", "root", "root", $options);
			$this->engagement_id = $engagement_id;
			$this->sponsor_id = $sponsor_id;
		}

		public function setEngagementId(int $engagement_id): string
		{
			if(empty($engagement_id)) {
				return "Please select a volunteer.";
			}
			else {
				$this->engagement_id = $engagement_id;
				return "";
			}
		}

		public function setSponsorId(int $sponsor_id): string
		{
			if(empty($sponsor_id)) {
				return "Please select an event.";
			}
			else {
				$this->sponsor_id = $sponsor_id;
				return "";
			}
		}

    public function deleteEngagement(): bool
    {
      $sql = "DELETE FROM engagements WHERE engagement_id = :engagement_id AND sponsor_id = :sponsor_id";
			$stmt = $this->pdo->prepare($sql);
			$status = $stmt->execute(
				[
					'engagement_id' => $this->engagement_id,
					'sponsor_id' => $this->sponsor_id,
				]);

      return $status;
    }
}
?>
