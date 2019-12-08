<?php
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
		// private $engagementCreationObject;

		public function __construct(int $sponsor_id, int $event_id, int $opportunity_id, float $contribution_value)
    {
      $options = [
			    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			    PDO::ATTR_EMULATE_PREPARES   => false,
			];
      $this->pdo = new PDO("mysql:host=localhost;dbname=volunteer_nexus;charset=utf8mb4", "root", "root", $options);
			$this->sponsor_id = $sponsor_id;
			$this->event_id = $event_id;
			$this->opportunity_id = $opportunity_id;
			$this->contribution_value = $contribution_value;
			$this->status = TRUE;
		}

		public function setVolunteerId(string $student_id): bool
		{
			if(empty($sponsor_name) || strlen($student_id) != 6) {
				return false;
			}
			else {
				$sql = "SELECT volunteer_id FROM volunteers WHERE student_id = :student_id";
				$stmt = $pdo->prepare($sql);
				$stmt->execute([':student_id' => $student_id]);
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

		private function confirmAttendance(): bool
		{
			$sql = "SELECT engagement_id FROM engagements
			WHERE sponsor_id = :sponsor_id
				AND event_id = :event_id
				AND opportunity_id = :opportunity_id
				AND volunteer_id = :volunteer_id";
			$stmt = $pdo->prepare($sql);
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
				updateEngagement($engagement_id);
			}
			else {
				// create new engagement
				addEngagement();
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
			$sql = "INSERT INTO engagements (volunteer_id, event_id, opportunity_id, sponsor_id, contribution_value, status)
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
