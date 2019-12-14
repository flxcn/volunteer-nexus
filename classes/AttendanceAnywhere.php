<?php
require 'DatabaseConnection.php';

class AttendanceAnywhere
{
    protected $pdo = null;
		private $event_id;
		private $opportunity_id;
		private $student_id;
		private $volunteer_id;
		private $sponsor_id;
		private $contribution_value;
		private $status;

		public function __construct(int $sponsor_id, int $event_id, int $opportunity_id, float $contribution_value)
    {
      $this->pdo = (new DatabaseConnection)->getPDO();
			$this->sponsor_id = $sponsor_id;
			$this->event_id = $event_id;
			$this->opportunity_id = $opportunity_id;
			$this->contribution_value = $contribution_value;
			$this->status = TRUE;
		}

		public function setVolunteerId(string $student_id): bool
		{
			if(empty($student_id) || strlen($student_id) != 5) {
        return false;
			}
			else {
				$sql = "SELECT volunteer_id FROM volunteers WHERE student_id = :student_id";
				$stmt = $this->pdo->prepare($sql);
				$stmt->execute(['student_id' => $student_id]);
				$volunteer_id = $stmt->fetchColumn();

				if($volunteer_id) {
					$this->volunteer_id = $volunteer_id;
          return true;
				}
				else {
					return false;
				}
			}
		}

		public function confirmAttendance(): bool
		{
			$sql =
      "SELECT
        engagement_id
      FROM
        engagements
			WHERE
        sponsor_id = :sponsor_id
				AND event_id = :event_id
				AND opportunity_id = :opportunity_id
				AND volunteer_id = :volunteer_id";
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute(
				[
					'sponsor_id' => $this->sponsor_id,
					'event_id' => $this->event_id,
					'opportunity_id' => $this->opportunity_id,
					'volunteer_id' => $this->volunteer_id
				]);
			$engagement_id = $stmt->fetchColumn();

			if($engagement_id) {
				// update existing engagement
				return $this->updateEngagement($engagement_id);
			}
			else {
				// create new engagement
				return $this->addEngagement();
			}
		}

    private function updateEngagement($engagement_id): bool
    {
			$stmt = $this->pdo->prepare("UPDATE engagements SET status = 1 WHERE engagement_id = :engagement_id");
			$status = $stmt->execute(['engagement_id' => $engagement_id]);
			return $status;
    }

		private function addEngagement(): bool
    {
			$sql =
      "INSERT INTO engagements (volunteer_id, event_id, opportunity_id, sponsor_id, contribution_value, status)
				VALUES (:volunteer_id, :event_id, :opportunity_id, :sponsor_id, :contribution_value, :status)";
			$stmt = $this->pdo->prepare($sql);
			$status = $stmt->execute(
				[
					'volunteer_id' => $this->volunteer_id,
					'event_id' => $this->event_id,
					'opportunity_id' => $this->opportunity_id,
					'sponsor_id' => $this->sponsor_id,
					'contribution_value' => $this->contribution_value,
					'status' => $this->status
				]);

			return $status;
    }
}
?>
