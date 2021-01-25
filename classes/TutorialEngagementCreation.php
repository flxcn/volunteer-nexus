<?php
require_once 'DatabaseConnection.php';

class TutorialEngagementCreation
{
    protected $pdo = null;
    private $volunteer_id;
    private $event_id;
    private $opportunity_id;
    private $sponsor_id;
    private $contribution_value;
    private $status;
    private $time_submitted;

    public function __construct($volunteer_id, $sponsor_id)
    {
        $this->volunteer_id = $volunteer_id;
        $this->sponsor_id = $sponsor_id;
        $this->event_id = "";
        $this->opportunity_id = "";
        $this->sponsor_id = "";
        $this->contribution_value = "";
        $this->status = "";
        //$this->time_submitted = "";
    }

    public function setEngagementId(int $engagement_id): int
    {
        $this->engagement_id = $engagement_id;
        return $this->engagement_id;
    }
    
    public function setVolunteerId(int $volunteer_id): int
    {
        $this->volunteer_id = $volunteer_id;
        return $this->volunteer_id;
    }
    
    public function setEventId(int $event_id): int
    {
        $this->event_id = $event_id;
        return $this->event_id;
    }
    
    public function setOpportunityId(int $opportunity_id): int
    {
        $this->opportunity_id = $opportunity_id;
        return $this->opportunity_id;
    }
    
    public function setSponsorId(int $sponsor_id): int
    {
        $this->sponsor_id = $sponsor_id;
        return $this->sponsor_id;
    }
    
    public function setContributionValue(float $contribution_value): float
    {
        $this->contribution_value = $contribution_value;
        return $this->contribution_value;
    }
    
    public function setStatus(bool $status): bool
    {
        $this->status = $status;
        return $this->status;
    }

    public function getEngagement(): bool
    {
        $sql = "SELECT * FROM engagements WHERE engagement_id = :engagement_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['engagement_id' => $this->engagement_id]);
        $engagement = $stmt->fetch();

        if ($engagement)
        {
            $this->engagement_id = $engagement["engagement_id"];
            $this->volunteer_id = $engagement["volunteer_id"];
            $this->event_id = $engagement["event_id"];
            $this->opportunity_id = $engagement["opportunity_id"];
            $this->sponsor_id = $engagement["sponsor_id"];
            $this->contribution_value = $engagement["contribution_value"];
            $this->status = $engagement["status"];

            return true;
        } else 
        {
            return false;
        }
    }

    public function addEngagement(): bool
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

    // NOTE: still needs work
    public function addTutorialEvent(): bool
    {
        $sql = "INSERT INTO events (volunteer_id, event_id, opportunity_id, sponsor_id, contribution_value, status)
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

        // immediately get the value of the newly generated event id

        return $status;
    }
    
    public function updateEngagement(): bool
    {
        $sql = "SELECT * FROM engagements WHERE engagement_id = :engagement_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['engagement_id' => $this->engagement_id]);
        $engagement = $stmt->fetch();

        if ($engagement)
        {
            $this->engagement_id = $engagement["engagement_id"];
            $this->volunteer_id = $engagement["volunteer_id"];
            $this->event_id = $engagement["event_id"];
            $this->opportunity_id = $engagement["opportunity_id"];
            $this->sponsor_id = $engagement["sponsor_id"];
            $this->contribution_value = $engagement["contribution_value"];
            $this->status = $engagement["status"];

            return true;
        } else 
        {
            return false;
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
            ]
        );

        return $status;
    }

    public function doesTutorialEventExist(): bool 
    {
        $sql = "SELECT event_id FROM events WHERE sponsor = :sponsor_id AND event_name = :event_name";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['engagement_id' => $this->engagement_id, 'sponsor_id' => $this->engagement_id, 'event_name' => 'Tutoring']);
        $event = $stmt->fetch();

        if ($event)
        {
            // problematic b/c it is an undisclosed operation within what is ostensibly a checker function
            $this->event_id = $event["event_id"];
            return true;
        } else 
        {
            return false;
        }
    }

    public function getEngagementId(): int
    {
        return $this->engagement_id;
    }
    
    public function getVolunteerId(): int
    {
        return $this->volunteer_id;
    }
    
    public function getEventId(): int
    {
        return $this->event_id;
    }
    
    public function getOpportunityId(): int
    {
        return $this->opportunity_id;
    }
    
    public function getSponsorId(): int
    {
        return $this->sponsor_id;
    }
    
    public function getContributionValue(): float
    {
        return $this->contribution_value;
    }
    
    public function getStatus(): bool
    {
        return $this->status;
    }
}
?>
