<?php
require_once 'DatabaseConnection.php';

class VolunteerAccountReader
{
		protected $pdo = null;
		private $username;
		//private $password;
		private $volunteer_id;
		private $first_name;
        private $last_name;
        private $student_id;
        private $graduation_year;
        private $time_created;

	public function __construct($volunteer_id)
    {
        $this->pdo = DatabaseConnection::instance();
        $this->volunteer_id = $volunteer_id;
        $this->username = "";
        //$this->password = "";
        $this->first_name = "";
        $this->last_name = "";
        $this->graduation_year = "";
        $this->student_id = "";
        $this->time_created = "";
	}

    public function getVolunteerDetails(): bool
    {
        $sql =
            "SELECT
                username,
                first_name,
                last_name,
                graduation_year,
                student_id
            FROM
                volunteers
            WHERE
                volunteer_id = :volunteer_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['volunteer_id' => $this->volunteer_id]);
        $volunteer = $stmt->fetch();

        if ($volunteer)
        {
            $this->username = $volunteer["username"];
            $this->first_name = $volunteer["first_name"];
            $this->last_name = $volunteer["last_name"];
            $this->graduation_year = $volunteer["graduation_year"];
            $this->student_id = $volunteer["student_id"];

            return true;
        } else {
        return false;
        }
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getFirstName(): string
    {
        return $this->first_name;
    }

    public function getLastName(): string
    {
        return $this->last_name;
    }
    
    public function getFullName(): string
    {
        return $this->first_name . " " . $this->last_name;
    }
    
    public function getStudentId(): string
    {
        if($this->student_id) {
            return $this->student_id;
        }
        else {
            return "N/A";
        }
    }

    public function getGraduationYear(): string
    {
        if($this->graduation_year) {
            return $this->graduation_year;
        } else {
            return "N/A";
        }
    }
    
    public function getTimeCreated(): string
    {
        $timestamp = strtotime($time_created);
        return date("F Y", $timestamp);
    }
}
?>
