<?php
require_once 'DatabaseConnection.php';

class TutorialEngagementCreation
{
    protected $pdo = null;
    private $volunteer_id;
    private $event_id;
    private $opportunity_id;
    private $sponsor_id;
    private $status;
    private $start_date;
    private $end_date;
    private $start_time;
    private $end_time;
    private $contribution_value; 
    // private $needs_verification;
    // private $needs_reminder;
    // private $time_submitted;

    public function __construct($volunteer_id, $sponsor_id)
    {
        $this->pdo = DatabaseConnection::instance();
        $this->volunteer_id = $volunteer_id;
        $this->sponsor_id = $sponsor_id;
        $this->event_id = "";
        $this->opportunity_id = "";
        $this->sponsor_id = "";
        $this->contribution_value = "";
        $this->status = null;
        $this->needs_verification = 1;
        $this->needs_reminder = 0;
        $this->time_submitted = "";
    }

    public function setEngagementId(int $engagement_id)
    {
        $this->engagement_id = $engagement_id;
        // return $this->engagement_id;
    }
    
    public function setVolunteerId(int $volunteer_id)
    {
        $this->volunteer_id = $volunteer_id;
        // return $this->volunteer_id;
    }
    
    public function setEventId(int $event_id)
    {
        $this->event_id = $event_id;
        // return $this->event_id;
    }
    
    public function setOpportunityId(int $opportunity_id)
    {
        $this->opportunity_id = $opportunity_id;
        // return $this->opportunity_id;
    }
    
    public function setSponsorId($sponsor_id)
    {
        if(empty($sponsor_id)) 
        {
            return "Please select a Sponsor";
        }
        else {
            $this->sponsor_id = $sponsor_id;
            return "";
        } 
    }

    public function setSponsorName(string $sponsor_name): string
    {
        if(strcmp($sponsor_name, 'Select Sponsor') == 0) 
        {
            return "Please select a Sponsor";
        }
        else {
            $this->sponsor_name = $sponsor_name;
            // return $this->sponsor_name;
            return "";
        } 
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }
    
    public function setContributionValue(float $contribution_value)
    {
        $this->contribution_value = $contribution_value;
        // return $this->contribution_value;
    }
    
    public function setStatus(bool $status): bool
    {
        $this->status = $status;
        return $this->status;
    }

    public function setStartTime(string $start_time)
    {
        $this->start_time = $start_time;
        return "";
    }

    public function setEndTime(string $end_time)
    {
        $this->end_time = $end_time;
        return "";
    }

    public function setStartDate(string $start_date)
    {
        $this->start_date = $start_date;
        return "";
    }

    public function setEndDate(string $end_date)
    {
        $this->end_date = $end_date;
        return "";
    }

    public function setOpportunityName(string $last_name, string $first_name) 
    {
        $this->opportunity_name = $last_name . ", " . $first_name . " - Tutoring - " . date('Y-m-d H:i:s');

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

    // NOTE: still needs work
    public function addTutorialEvent(): bool
    {
        $sql = "INSERT INTO events (sponsor_id, event_name, sponsor_name, sponsor_id, contribution_value, status)
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

    public function addTutorial():bool {
        if($this->addTutorialOpportunity()) {
            $this->opportunity_id = $this->pdo->lastInsertId();
            if($this->addTutorialEngagement()) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    public function addTutorialOpportunity(): bool
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
            VALUES 
                (:event_id, 
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
                'total_positions' => 1,
                'limit_per_volunteer' => 1,
                'contribution_value' => $this->contribution_value,
                'needs_verification' => 1,
                'needs_reminder' => 0
            ]);

        return $status;
    }

    public function addTutorialEngagement(): bool
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
                'status' => null
            ]);

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
        $sql = "SELECT event_id FROM events WHERE sponsor_id = :sponsor_id AND event_name = :event_name";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['sponsor_id' => $this->sponsor_id, 'event_name' => 'Tutoring 2020-2021']);
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
