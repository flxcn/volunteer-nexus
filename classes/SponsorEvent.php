<?php
require_once 'DatabaseConnection.php';

class SponsorEvent
{
    protected $pdo = null;

    private $event_id;
    private $sponsor_id;

    private $event_name;
    private $sponsor_name;
    private $description;
    private $location;
    private $contribution_type;

    private $contact_name;
    private $contact_phone;
    private $contact_email;

    private $registration_start;
    private $registration_end;
    private $event_start;
    private $event_end;

    private $is_public;

    private $time_posted;

	public function __construct($sponsor_id)
    {
        $this->pdo = DatabaseConnection::instance();

        $this->sponsor_id = $sponsor_id;
        $this->event_id = "";
        $this->event_name = "";
        $this->sponsor_name = "";
        $this->description = "";
        $this->location = "";
        $this->contribution_type = "";

        $this->contact_name = "";
        $this->contact_phone = "";
        $this->contact_email = "";

        $this->registration_start = "";
        $this->registration_end = "";
        $this->event_start = "";
        $this->event_end = "";

        $this->is_public = false;

        $this->time_posted = "";
    }

    public function setEventName(string $event_name): string
    {
        if(empty($event_name)) {
            return "Please enter the name of this event.";
        }
        else {
            $this->event_name = $event_name;
            return "";
        }
    }

    public function setSponsorName(string $sponsor_name): string
    {
        if(empty($sponsor_name)) {
            return "Please enter the name of the sponsor.";
        }
        else {
            $this->sponsor_name = $sponsor_name;
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

    public function setLocation(string $location): string
    {
        if(empty($location)) {
            return "Please enter a location.";
        }
        else {
            $this->location = $location;
            return "";
        }
    }

    public function setContributionType(string $contribution_type): string
    {
        if(empty($location)) {
            return "Please enter a contribution type.";
        }
        else {
            $this->contribution_type = $contribution_type;
            return "";
        }
    }

    public function setContactName(string $contact_name): ?string
    {
        if(empty($contact_name)) {
            return "Please enter the contact name.";
        }
        else {
            $this->contact_name = $contact_name;
            return "";
        }
    }

    public function setContactPhone(string $contact_phone): ?string
    {
        $this->contact_phone = $contact_phone;
        return "";
    }

    public function setContactEmail(string $contact_email): ?string
    {
        if(empty($contact_email)) {
            return "Please enter the contact email.";
        }
        else {
            $this->contact_email = $contact_email;
            return "";
        }
    }

    public function setRegistrationStart(string $registration_start): string
    {
        if(empty($registration_start)) {
            return "Please enter a registration start date.";
        }
        else {
            $this->registration_start = $registration_start;
            return "";
        }
    }

    public function setRegistrationEnd(string $registration_end): string
    {
        if(empty($registration_end)) {
            return "Please enter a registration end date.";
        }
        else {
            $this->registration_end = $registration_end;
            return "";
        }
    }

    public function setEventStart(string $event_start): string
    {
        if(empty($event_start)) {
            return "Please enter an event start date.";
        }
        else {
            $this->event_start = $event_start;
            return "";
        }
    }

    public function setEventEnd(string $event_end): string
    {
        if(empty($event_end)) {
            return "Please enter an event end date.";
        }
        else {
            $this->event_end = $event_end;
            return "";
        }
    }

    public function setIsPublic($is_public): string
    {
        $this->is_public = $is_public;
        return "";
    }

    public function getEventName(): string
    {
        return $this->event_name;
    }

    public function getSponsorName(): string
    {
        return $this->sponsor_name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getContributionType(): string
    {
        return $this->contribution_type;
    }

    public function getContactName(): string
    {
        return $this->contact_name;
    }

    public function getContactPhone(): string
    {
        return $this->contact_phone;
    }

    public function getContactEmail(): string
    {
        return $this->contact_email;
    }

    public function getRegistrationStart(): string
    {
        return $this->registration_start;
    }

    public function getRegistrationEnd(): string
    {
        return $this->registration_end;
    }

    public function getEventStart(): string
    {
        return $this->event_start;
    }

    public function getEventEnd(): string
    {
        return $this->event_end;
    }

    public function getIsPublic()
    {
        return $this->is_public;
    }

    public function getTimePosted(): bool
    {
        return $this->time_posted;
    }

    public function getEvent($event_id): bool
	{
		$sql =
            "SELECT *
            FROM        events
            WHERE       event_id = :event_id";

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute(['event_id' => $event_id]);
		$event = $stmt->fetch();

		if($event) {
            $this->event_id = $event["event_id"];
            $this->sponsor_id = $event["sponsor_id"];
            $this->event_name = $event["event_name"];
            $this->sponsor_name = $event["sponsor_name"];
            $this->description = $event["description"];
            $this->location = $event["location"];
            $this->contribution_type = $event["contribution_type"];
            $this->contact_name = $event["contact_name"];
            $this->contact_phone = $event["contact_phone"];
            $this->contact_email = $event["contact_email"];
            $this->registration_start = $event["registration_start"];
            $this->registration_end = $event["registration_end"];
            $this->event_start = $event["event_start"];
            $this->event_end = $event["event_end"];
            $this->is_public = $event["is_public"];
            return true;
        }
		else {
			return false;
		}
    }

    public function updateEvent($event_id): bool 
	{
		$sql =
			"UPDATE events 
            SET     event_name = :event_name, 
                    sponsor_name = :sponsor_name, 
                    description = :description, 
                    location = :location, 
                    contact_name = :contact_name, 
                    contact_phone = :contact_phone, 
                    contact_email = :contact_email, 
                    registration_start = :registration_start, 
                    registration_end = :registration_end, 
                    event_start = :event_start, 
                    event_end = :event_end, 
                    is_public = :is_public 
            WHERE   event_id = :event_id
                    AND sponsor_id = :sponsor_id";
		$stmt = $this->pdo->prepare($sql);
		$status = $stmt->execute([
            'event_name' => $this->event_name, 
            'sponsor_name' => $this->sponsor_name, 
            'description' => $this->description, 
            'location' => $this->location, 

            'contact_name' => $this->contact_name, 
            'contact_phone' => $this->contact_phone, 
            'contact_email' => $this->contact_email, 

            'registration_start' => $this->registration_start, 
            'registration_end' => $this->registration_end, 
            'event_start' => $this->event_start, 
            'event_end' => $this->event_end, 

            'is_public' => $this->is_public, 

            'sponsor_id' => $this->sponsor_id, 
            'event_id' => $event_id
        ]);
		return $status;
	}

    public function removeEvent($event_id): bool 
	{
		$sql =
			"DELETE FROM 
				events
			WHERE
				sponsor_id = :sponsor_id
				AND event_id = :event_id";
		$stmt = $this->pdo->prepare($sql);
		$status = $stmt->execute(['sponsor_id' => $this->sponsor_id, 'event_id' => $event_id]);
		return $status;
	}

}
?>
