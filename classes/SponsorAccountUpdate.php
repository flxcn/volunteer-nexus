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
    private $advisor1_name;
    private $advisor1_email;
    private $advisor1_phone;
    private $advisor2_name;
    private $advisor2_email;
    private $advisor2_phone;
    private $advisor3_name;
    private $advisor3_email;
    private $advisor3_phone;

    public function __construct($sponsor_id)
    {
        $this->pdo = DatabaseConnection::instance();
        $this->sponsor_id = $sponsor_id;
        $this->username = "";
        $this->password = "";
        $this->confirm_password = "";
        $this->sponsor_name = "";
        $this->contribution_type = "";
        $this->advisors = [];
        $this->advisor1_name = "";
        $this->advisor1_email = "";
        $this->advisor1_phone = "";
        $this->advisor2_name = "";
        $this->advisor2_email = "";
        $this->advisor2_phone = "";
        $this->advisor3_name = "";
        $this->advisor3_email = "";
        $this->advisor3_phone = "";
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
            //$this->time_created = $sponsor["time_created"];

            return true;
        } else {
            return false;
        }
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

    public function setAdvisor1Name($advisor1_name)
    {
        $this->advisor1_name = $advisor1_name;
        return $advisor1_name;
    }

    public function setAdvisor1Email($advisor1_email)
    {
        $this->advisor1_email = $advisor1_email;
        return $advisor1_email;
    }

    public function setAdvisor1Phone($advisor1_phone)
    {
        $this->advisor1_phone = $advisor1_phone;
        return $advisor1_phone;
    }

    public function setAdvisor2Name($advisor2_name)
    {
        $this->advisor2_name = $advisor2_name;
        return $advisor2_name;
    }

    public function setAdvisor2Email($advisor2_email)
    {
        $this->advisor2_email = $advisor2_email;
        return $advisor2_email;
    }

    public function setAdvisor2Phone($advisor2_phone)
    {
        $this->advisor2_phone = $advisor2_phone;
        return $advisor2_phone;
    }

    public function setAdvisor3Name($advisor3_name)
    {
        $this->advisor3_name = $advisor3_name;
        return $advisor3_name;
    }

    public function setAdvisor3Email($advisor3_email)
    {
        $this->advisor3_email = $advisor3_email;
        return $advisor3_email;
    }

    public function setAdvisor3Phone($advisor3_phone)
    {
        $this->advisor3_phone = $advisor3_phone;
        return $advisor3_phone;
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
                'password' => password_hash($this->password, PASSWORD_DEFAULT),
                'sponsor_id' => $this->sponsor_id
            ]
        );
    
        return $status;
    }

    public function updateSponsor(): bool
    {
        $sql = 
            "UPDATE sponsors 
            SET 
                sponsor_name = :sponsor_name, 
                username = :username, 
                contribution_type = :contribution_type, 
                advisor1_name = :advisor1_name, 
                advisor1_email = :advisor1_email, 
                advisor1_phone = :advisor1_phone, 
                advisor2_name = :advisor2_name, 
                advisor2_email = :advisor2_email, 
                advisor2_phone = :advisor2_phone, 
                advisor3_name = :advisor3_name, 
                advisor3_email = :advisor3_email, 
                advisor3_phone = :advisor3_phone
            WHERE
                sponsor_id = :sponsor_id";
        $stmt = $this->pdo->prepare($sql);
        $status = $stmt->execute(
            [
                'sponsor_name' => $this->sponsor_name,
                'username' => $this->username,
                //'password' => password_hash($this->password, PASSWORD_DEFAULT),
                'contribution_type' => $this->contribution_type,
                'advisor1_name' => $this->advisor1_name,
                'advisor1_email' => $this->advisor1_email,
                'advisor1_phone' => $this->advisor1_phone,
                'advisor2_name' => $this->advisor2_name,
                'advisor2_email' => $this->advisor2_email,
                'advisor2_phone' => $this->advisor2_phone,
                'advisor3_name' => $this->aadvisor3_name,
                'advisor3_email' => $this->advisor3_email,
                'advisor3_phone' => $this->advisor3_phone,
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
