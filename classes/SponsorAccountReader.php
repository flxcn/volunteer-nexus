<?php
require_once 'DatabaseConnection.php';

class SponsorAccountReader
{
		protected $pdo = null;
		private $username;
		//private $password;
		private $sponsor_id;
		private $sponsor_name;
        private $contribution_type;
        private $advisor1_name;
        private $advisor1_email;
        private $advisor1_phone;
        private $advisor2_name;
        private $advisor2_email;
        private $advisor2_phone;
        private $advisor3_name;
        private $advisor3_email;
        private $advisor3_phone;
        private $time_created;

	public function __construct($sponsor_id)
    {
        $this->pdo = DatabaseConnection::instance();
        $this->sponsor_id = "";
        $this->username = "";
        //$this->password = "";
        $this->sponsor_name = "";
        $this->contribution_type = "";
        $this->advisor1_name = "";
        $this->advisor1_email = "";
        $this->advisor1_phone = "";
        $this->advisor2_name = "";
        $this->advisor2_email = "";
        $this->advisor2_phone = "";
        $this->advisor3_name = "";
        $this->advisor3_email = "";
        $this->advisor3_phone = "";
        $this->time_created = "";
	}

    public function getSponsorDetails(): bool
    {
        $sql =
            "SELECT
                username,
                sponsor_name,
                contribution_type,
                advisor1_name,
                advisor1_email,
                advisor1_phone,
                advisor2_name,
                advisor2_email,
                advisor2_phone,
                advisor3_name,
                advisor3_email,
                advisor3_phone,
                time_created
            FROM
                sponsors
            WHERE
                sponsor_id = :sponsor_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['sponsor_id' => $this->sponsor_id]);
        $sponsor = $stmt->fetch();

        if ($sponsor)
        {
            $this->username = $sponsor["username"];
            $this->sponsor_name = $sponsor["sponsor_name"];
            $this->contribution_type = $sponsor["contribution_type"];
            $this->advisor1_name = $sponsor["advisor1_name"];
            $this->advisor1_email = $sponsor["advisor1_email"];
            $this->advisor1_phone = $sponsor["advisor1_phone"];
            $this->advisor2_name = $sponsor["advisor2_name"];
            $this->advisor2_email = $sponsor["advisor2_email"];
            $this->advisor2_phone = $sponsor["advisor2_phone"];
            $this->advisor3_name = $sponsor["advisor3_name"];
            $this->advisor3_email = $sponsor["advisor3_email"];
            $this->advisor3_phone = $sponsor["advisor3_phone"];
            $this->time_created = $sponsor["time_created"];

            return true;
        } else {
            return false;
        }
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getSponsorName(): string
    {
        return $this->sponsor_name;
    }

    public function getContributionType(): string
    {
        return $this->contribution_type;
    }
    
    public function getAdvisor1Name(): string
    {
        return $this->advisor1_name;
    }

    public function getAdvisor1Email(): string
    {
        return $this->advisor1_email;
    }

    public function getAdvisor1Phone(): string
    {
        return $this->advisor1_phone;
    }

    public function getAdvisor2Name(): string
    {
        return $this->advisor2_name;
    }

    public function getAdvisor2Email(): string
    {
        return $this->advisor2_email;
    }

    public function getAdvisor2Phone(): string
    {
        return $this->advisor2_phone;
    }

    public function getAdvisor3Name(): string
    {
        return $this->advisor3_name;
    }

    public function getAdvisor3Email(): string
    {
        return $this->advisor3_email;
    }

    public function getAdvisor3Phone(): string
    {
        return $this->advisor3_phone;
    }
    
    public function getTimeCreated(): string
    {
        $timestamp = strtotime($time_created);
        return date("F Y", $timestamp);
    }
}
?>
