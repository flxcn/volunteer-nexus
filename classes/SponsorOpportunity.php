<?php
require_once 'DatabaseConnection.php';

class SponsorOpportunity
{
    protected $pdo = null;
	private $opportunity_id;
    private $event_id;
	private $sponsor_id;
	private $opportunity_name;
    private $description;
    private $start_date;
    private $end_date;
    private $start_time;
    private $end_time;
    private $total_positions;
    private $limit_per_volunteer;
	private $contribution_value;
	private $needs_verification;
    private $needs_reminder;

	public function __construct(int $sponsor_id)
    {
        $this->pdo = DatabaseConnection::instance();
        $this->opportunity_id = "";
        $this->event_id = 0;
		$this->sponsor_id = $sponsor_id;
		$this->opportunity_name = "";
        $this->description = "";
        $this->start_date = "";
        $this->end_date = "";
        $this->start_time = "";
        $this->end_time = "";
        $this->total_positions = "";
        $this->limit_per_volunteer = 1;
		$this->contribution_value = 0.0;
        $this->needs_verification = false;
		$this->needs_reminder = 0;
	}

    public function setEventId(int $event_id): string
	{
		if(empty($event_id)) {
			return "Please select an event.";
		}
		else {
			$this->event_id = $event_id;
			return "";
		}
	}

	public function setOpportunityName(string $opportunity_name): string
	{
		if(empty($opportunity_name)) {
			return "Please enter an opportunity name.";
		}
		else {
			$this->opportunity_name = $opportunity_name;
			return "";
		}
	}

    public function setDescription(string $description): string
	{
		if(empty($description)) {
			return "Please enter a description.";
		}
		else {
			$this->description = $description;
			return "";
		}
	}

    public function setStartDate(string $start_date): string
	{
		if(empty($start_date)) {
			return "Please enter a start date.";
		}
		else {
			$this->start_date = $start_date;
			return "";
		}
	}

    public function setEndDate(string $end_date): string
	{
		if(empty($end_date)) {
			return "Please enter an end date.";
		}
		else {
			$this->end_date = $end_date;
			return "";
		}
	}

    public function setStartTime(string $start_time): string
	{
		if(empty($start_time)) {
			return "Please enter a start time.";
		}
		else {
			$this->start_time = date("H:i", strtotime($start_time));
			return "";
		}
	}

    public function setEndTime(string $end_time): string
	{
		if(empty($end_time)) {
			return "Please enter an end time.";
		}
		else {
			$this->end_time = date("H:i", strtotime($end_time));
			return "";
		}
	}

    public function setTotalPositions(string $total_positions): string
	{
		if(empty($total_positions)) {
            $this->total_positions = null;
            return "";
		}
		else if(is_numeric($total_positions)) {
			$this->total_positions = $total_positions;
			return "";
		}
        else {
            return "Not a valid number";
        }
	}

    public function setLimitPerVolunteer(string $limit_per_volunteer): string
	{
		if(empty($limit_per_volunteer)) {
			return "Please enter the total number of sign-ups each volunteer is allowed.";
		}
		else {
			$this->limit_per_volunteer = $limit_per_volunteer;
			return "";
		}
	}

    public function setContributionValue($contribution_value): string
	{
		if(empty($contribution_value)) {
            $this->contribution_value = 0.0;
            return "";
		}
		else {
			$this->contribution_value = $contribution_value;
			return "";
		}
	}

	public function setNeedsReminder($needs_reminder): string
	{
		$this->needs_reminder = $needs_reminder;
		return "";
	}

    public function setNeedsVerification(string $needs_verification)
	{
        $this->needs_verification = $needs_verification;
		return "";
	}

    public function getOpportunity($opportunity_id): bool
    {
        $sql =
            "SELECT
                *
            FROM
                opportunities
            WHERE
                opportunity_id = :opportunity_id
                AND sponsor_id = :sponsor_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['opportunity_id' => $opportunity_id, 'sponsor_id' => $this->sponsor_id]);
        $opportunity = $stmt->fetch();

        if($opportunity) {
            $this->opportunity_name = $opportunity['opportunity_name'];
            $this->description = $opportunity['description'];
            $this->start_date = $opportunity['start_date'];
            $this->start_time = $opportunity['start_time'];
            $this->end_date = $opportunity['end_date'];
            $this->end_time = $opportunity['end_time'];
            $this->total_positions = $opportunity['total_positions'];
            $this->contribution_value = $opportunity['contribution_value'];
            $this->needs_verification = $opportunity['needs_verification'];
            $this->needs_reminder = $opportunity['needs_reminder'];
            $this->limit_per_volunteer = $opportunity['limit_per_volunteer'];
            return true;
        }
        else {
            return false;
        }
    }

    public function addOpportunity(): bool
    {
        $sql = 
            "INSERT INTO opportunities 
                        (event_id, 
                         sponsor_id, 
                         opportunity_name, 
                         description, 
                         start_date, 
                         end_date, 
                         start_time, 
                         end_time, 
                         total_positions, 
                         limit_per_volunteer, 
                         contribution_value, 
                         needs_verification, 
                         needs_reminder) 
            VALUES      (:event_id,
                         :sponsor_id,
                         :opportunity_name,
                         :description, 
                         :start_date, 
                         :end_date, 
                         :start_time, 
                         :end_time, 
                         :total_positions, 
                         :limit_per_volunteer, 
                         :contribution_value, 
                         :needs_verification, 
                         :needs_reminder)";
                
		$stmt = $this->pdo->prepare($sql);
		$status = $stmt->execute(
			[
				'event_id' => $this->event_id,
				'sponsor_id' => $this->sponsor_id,
				'opportunity_name' => $this->opportunity_name,
				'description' => $this->description,
				'start_date' => $this->start_date,
                'end_date' => $this->end_date,
				'start_time' => $this->start_time,
				'end_time' => $this->end_time,
				'total_positions' => $this->total_positions,
                'limit_per_volunteer' => $this->limit_per_volunteer,
				'contribution_value' => $this->contribution_value,
				'needs_verification' => $this->needs_verification,
				'needs_reminder' => $this->needs_reminder
			]);

		return $status;
	}

    public function updateOpportunity($opportunity_id): bool
    {
        $sql =
            "UPDATE opportunities
            SET
                opportunity_name = :opportunity_name,
                description = :description,
                start_date = :start_date,
                start_time = :start_time,
                end_date = :end_date,
                end_time = :end_time,
                total_positions = :total_positions,
                contribution_value = :contribution_value,
                needs_verification = :needs_verification,
                needs_reminder = :needs_reminder,
                limit_per_volunteer = :limit_per_volunteer
            WHERE
                opportunity_id= :opportunity_id
                AND sponsor_id = :sponsor_id";
        $stmt = $this->pdo->prepare($sql);
        $status = $stmt->execute(
            [
                'opportunity_name' => $this->opportunity_name,
                'description' => $this->description,
                'start_date' => $this->start_date,
                'start_time' => $this->start_time,
                'end_date' => $this->end_date,
                'end_time' => $this->end_time,
                'total_positions' => $this->total_positions,
                'contribution_value' => $this->contribution_value,
                'needs_verification' => $this->needs_verification,
                'needs_reminder' => $this->needs_reminder,
                'limit_per_volunteer' => $this->limit_per_volunteer,
                'opportunity_id' => $opportunity_id,
                'sponsor_id' => $this->sponsor_id
            ]
        );
        return $status;
    }

    public function getOpportunityName(): string
    {
        return $this->opportunity_name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStartDate(): string
    {
        return $this->start_date;
    }

    public function getStartTime(): string
    {
        return $this->start_time;
    }

    public function getEndDate(): string
    {
        return $this->end_date;
    }

    public function getEndTime(): string
    {
        return $this->end_time;
    }

    public function getTotalPositions(): ?int
    {
        return $this->total_positions;
    }

    public function getContributionValue(): float
    {
        return $this->contribution_value;
    }

    public function getNeedsVerification(): int
    {
        if($this->needs_verification) {
            return 1;
        }
        else {
            return 0;
        }
    }

    public function getNeedsReminder(): bool
    {
        return $this->needs_reminder;
    }

    public function getLimitPerVolunteer(): string
    {
        return $this->limit_per_volunteer;
    }

}
?>
