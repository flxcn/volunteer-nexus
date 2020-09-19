<?php
require_once 'DatabaseConnection.php';

class SponsorAccountUpdate
{
    protected $pdo = null;
    private $sponsor_id;
    private $sponsor_name;
    private $username;
    private $password;
    private $confirm_password;
    private $contribution_type;
	private $advisors;

    public function __construct($volunteer_id)
    {
        $this->pdo = DatabaseConnection::instance();
        $this->sponsor_id = $sponsor_id;
        $this->username = "";
        $this->password = "";
        $this->confirm_password = "";
        $this->sponsor_name = "";
        $this->contribution_type = "";
        $this->advisors = "";
    }

    public function setSponsorName(string $sponsor_name): string
    {
        if(empty($sponsor_name)) {
            return "Please enter the name of your sponsoring organization.";
        }
        else {
            $this->sponsor_name = $sponsor_name;
            return "";
        }
    }

    public function setUsername(string $username): string
    {
        $stmt = $this->pdo->prepare('SELECT count(*) FROM sponsors WHERE username = :username');
        $stmt->execute(['username' => strtolower($username)]);
        $same_usernames = $stmt->fetchColumn();
        if($same_usernames > 0){
            return "This username is already taken.";
        }
        if(!filter_var($username, FILTER_VALIDATE_EMAIL))
        {
            return "This email address is not valid";
        } else {
            $this->username = strtolower($username);
            return "";
        }
    }

    public function setPassword(string $password): string
    {
        if(empty($password)) {
            return "Please enter a password.";
        }
        elseif(strlen($password) < 6) {
            return "Password must have at least 6 characters.";
        }
        else {
            $this->password = $password;
            return "";
        }
    }

    public function setConfirmPassword(string $confirm_password): string
    {
        if(empty($confirm_password)) {
            return "Please confirm password.";
        }

        if(strcmp($this->password,$confirm_password) != 0){
            return "Password did not match.";
        }

        $this->confirm_password = $confirm_password;
        return "";
    }

    public function setContributionType(string $contribution_type): string
    {
        if(empty($contribution_type)) {
            return "Please enter a contribution type.";
        }
        else {
            $this->contribution_type = $contribution_type;
            return "";
        }
    }

    public function addAdvisor(string $advisor_name, string $advisor_email, string $advisor_phone)
    {
        $advisor = array("name" => $advisor_name, "email" => $advisor_email, "phone" => $advisor_phone);
        array_push($this->advisors, $advisor);
    }

    public function updateSponsorName(): bool
    {
        $sql =
            "UPDATE sponsors
            SET
                sponsor_name = :sponsor_name
            WHERE
                sponsor_id= :sponsor_id";
        $stmt = $this->pdo->prepare($sql);
        $status = $stmt->execute(
            [
                'sponsor_name' => $this->sponsor_name,
                'sponsor_id' => $this->sponsor_id
            ]
        );
    
        return $status;
    }

    public function updatePassword(): bool
    {
        $sql =
            "UPDATE sponsors
            SET
                password = :password
            WHERE
                sponsor_id= :sponsor_id";
        $stmt = $this->pdo->prepare($sql);
        $status = $stmt->execute(
            [
                'password' => $this->password_hash($this->password, PASSWORD_DEFAULT),
                'sponsor_id' => $this->sponsor_id
            ]
        );
    
        return $status;
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

    public function getAdvisor1Phone(): ?string
    {
        return $this->advisor1_phone;
    }

    public function getAdvisor2Name(): ?string
    {
        return $this->advisor2_name;
    }

    public function getAdvisor2Email(): ?string
    {
        return $this->advisor2_email;
    }

    public function getAdvisor2Phone(): ?string
    {
        return $this->advisor2_phone;
    }

    public function getAdvisor3Name(): ?string
    {
        return $this->advisor3_name;
    }

    public function getAdvisor3Email(): ?string
    {
        return $this->advisor3_email;
    }

    public function getAdvisor3Phone(): ?string
    {
        return $this->advisor3_phone;
    }

    public function getTimeCreated(): string
    {
        $timestamp = strtotime($this->time_created);
        return date("F Y", $timestamp);
    }

}
?>
