<?php
require_once 'DatabaseConnection.php';

class VolunteerRegistration
{
    protected $pdo = null;
    private $first_name;
    private $last_name;
    private $username;
    private $password;
    private $confirm_password;
    private $graduation_year;

    public function __construct()
    {
        $this->pdo = DatabaseConnection::instance();
        $this->first_name = "";
        $this->last_name = "";
        $this->username = "";
        $this->password = "";
        $this->confirm_password = "";
        $this->graduation_year = "";
    }

    public function setFirstName(string $first_name): string
    {
        if(empty($first_name)) {
            return "Please enter your first name.";
        }
        else {
            $this->first_name = $first_name;
            return "";
        }
    }

    public function setLastName(string $last_name): string
    {
        if(empty($last_name)) {
            return "Please enter your first name.";
        }
        else {
            $this->last_name = $last_name;
            return "";
        }
    }

    public function setUsername(string $username): string
    {
        $stmt = $this->pdo->prepare('SELECT count(*) FROM volunteers WHERE username = :username');
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

    public function setGraduationYear(string $graduation_year): string
    {
        if(empty($graduation_year)) {
            return "Please enter a graduation year.";
        }
        else {
            $this->graduation_year = $graduation_year;
            return "";
        }
    }

    public function addVolunteer(): bool
    {
        $sql = 
            "INSERT INTO volunteers (first_name, last_name, username, password, graduation_year)
            VALUES (:first_name, :last_name, :username, :password, :graduation_year)";
        $stmt = $this->pdo->prepare($sql);
        $status = $stmt->execute(
            [
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'username' => $this->username,
                'password' => password_hash($this->password, PASSWORD_DEFAULT),
                'graduation_year' => $this->graduation_year
            ]);

        return $status;
    }
}
?>
