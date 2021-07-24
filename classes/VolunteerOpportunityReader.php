<?php
require_once 'DatabaseConnection.php';

class VolunteerOpportunityReader
{
    protected $pdo = null;
    private $opportunity_id;
    private $event_id;
    private $volunteer_id;
    private $opportunity_name;
    private $description;
    private $start_date;
    private $start_time;
    private $end_date;
    private $end_time;
    private $total_positions;
    private $contribution_value;
    private $needs_verification;
    private $needs_reminder;

	public function __construct($volunteer_id, $event_id)
    {
        $this->pdo = DatabaseConnection::instance();
        $this->volunteer_id = $volunteer_id;
        $this->opportunity_id = "";
        $this->event_id = $event_id;
        $this->opportunity_name = "";
        $this->description = "";
        $this->start_date = "";
        $this->start_time = "";
        $this->end_date = "";
        $this->end_time = "";
        $this->total_positions = 0;
        $this->contribution_value = 0.0;
        $this->needs_verification = false;
        $this->needs_reminder = false;
	}

    public function setOpportunityName(string $opportunity_name): string
    {
        if(empty($opportunity_name)) {
            return "Please enter the name of this opportunity.";
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

    // NOTE: Consider implementing float for these values, as hours can be fractional. Explore PHP float issues first.
    public function setContributionValue($contribution_value): string
    {
        if(empty($contribution_value)) {
            return "Please enter a contribution value.";
        }
        else {
            $this->contribution_value = $contribution_value;
            return "";
        }
    }

    public function setTotalPositions(int $total_positions): string
    {
        if(empty($total_positions)) {
            return "Please enter the total number of positions.";
        }
        else {
            $this->total_positions = $total_positions;
            return "";
        }
    }

    public function setStartDate(string $start_date): string
    {
        if(empty($start_date)) {
            return "Please enter an opportunity start date.";
        }
        else {
            $this->start_date = $start_date;
            return "";
        }
    }

    public function setStartTime(string $start_time): string
    {
        if(empty($start_time)) {
            return "Please enter an opportunity start time.";
        }
        else {
            $this->start_time = $start_time;
            return "";
        }
    }

    public function setEndDate(string $end_date): string
    {
        if(empty($end_date)) {
            return "Please enter an opportunity end date.";
        }
        else {
            $this->end_date = $end_date;
            return "";
        }
    }

    public function setEndTime(string $end_time): string
    {
        if(empty($end_time)) {
            return "Please enter an opportunity end time.";
        }
        else {
            $this->end_time = $end_time;
            return "";
        }
    }

    public function setNeedsVerification(bool $needs_verification): string
    {
        $this->needs_verification = $needs_verification;
        return "";
    }

    public function setNeedsReminder(bool $needs_reminder): string
    {
        $this->needs_reminder = $needs_reminder;
        return "";
    }

    public function getOpportunities(): ?array
	{
		$sql =
            "SELECT     o.opportunity_id        AS opportunity_id, 
                        o.event_id              AS event_id, 
                        o.contribution_value    AS contribution_value,
                        opportunity_name, 
                        description, 
                        start_date, 
                        start_time, 
                        end_date, 
                        end_time, 
                        total_positions, 
                        COUNT(engagement_id)    AS positions_filled
            FROM        opportunities AS o
            LEFT JOIN   engagements AS e
            ON          o.opportunity_id = e.opportunity_id
            WHERE       o.event_id = :event_id
            GROUP BY    opportunity_name, 
                        description, 
                        start_date, 
                        start_time, 
                        end_date, 
                        end_time, 
                        total_positions, 
                        o.opportunity_id,
                        o.contribution_value";

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute(['event_id' => $this->event_id]);
		$opportunities = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if(!$opportunities) {
			return null;
		}
		else {
			return $opportunities;
		}
    }

	public function getOpportunity(): bool
    {
        $sql =
            "SELECT
                *
            FROM
                opportunities
            WHERE
                opportunity_id = :opportunity_id";

		$stmt = $this->pdo->prepare($sql);
		$opportunity = $stmt->execute(['opportunity_id' => $this->opportunity_id])->fetch(PDO::FETCH_ASSOC);

        if($opportunity) {
            $this->setOpportunityName($opportunity['opportunity_name']);
            $this->setDescription($opportunity['description']);
            $this->setStartDate($opportunity['start_date']);
            $this->setStartTime($opportunity['start_time']);
            $this->setEndDate($opportunity['end_date']);
            $this->setEndTime($opportunity['end_time']);
            $this->setTotalPositions($opportunity['total_positions']);
            $this->setContributionValue($opportunity['contribution_value']);
            $this->setNeedsVerification($opportunity['needs_verification']);
            $this->setNeedsReminder($opportunity['needs_reminder']);
            return true;
        }
        else {
            return false;
        }
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

    public function getTotalPositions(): int
    {
        return $this->start_date;
    }

    public function getContributionValue(): float
    {
        return $this->contribution_value;
    }

    public function getNeedsVerification(): bool
    {
        return $this->needs_verification;
    }

    public function getNeedsReminder(): bool
    {
        return $this->needs_reminder;
    }
}
?>
