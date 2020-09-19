<?php
require_once 'DatabaseConnection.php';

class VolunteerAccountUpdate
{
    protected $pdo = null;
    private $username;
    private $password;
    private $volunteer_id;
    private $first_name;
    private $last_name;
    private $student_id;
    private $graduation_year;

    public function __construct($volunteer_id)
    {
        $this->pdo = DatabaseConnection::instance();
        $this->volunteer_id = $volunteer_id;
        $this->username = "";
        $this->password = "";
        $this->first_name = "";
        $this->last_name = "";
        $this->student_id = "";
        $this->graduation_year = "";
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

    public function updateFirstName(): bool
    {
        $sql =
            "UPDATE volunteers
            SET
                first_name = :first_name
            WHERE
                volunteer_id= :volunteer_id";
        $stmt = $this->pdo->prepare($sql);
        $status = $stmt->execute(
            [
                'first_name' => $this->first_name,
                'volunteer_id' => $this->volunteer_id
            ]
        );
    
        return $status;
    }

    public function updateLastName(): bool
    {
        $sql =
            "UPDATE volunteers
            SET
                last_name = :last_name
            WHERE
                volunteer_id= :volunteer_id";
        $stmt = $this->pdo->prepare($sql);
        $status = $stmt->execute(
            [
                'last_name' => $this->last_name,
                'volunteer_id' => $this->volunteer_id
            ]
        );
    
        return $status;
    }

    public function updateStudentId(): bool
    {
        $sql =
            "UPDATE volunteers
            SET
                student_id = :student_id
            WHERE
                volunteer_id= :volunteer_id";
        $stmt = $this->pdo->prepare($sql);
        $status = $stmt->execute(
            [
                'student_id' => $this->student_id,
                'volunteer_id' => $this->volunteer_id
            ]
        );
    
        return $status;
    }

	public function updateGraduationYear(): bool
    {
        $sql =
            "UPDATE volunteers
            SET
                graduation_year = :graduation_year
            WHERE
                volunteer_id= :volunteer_id";
        $stmt = $this->pdo->prepare($sql);
        $status = $stmt->execute(
            [
                'graduation_year' => $this->graduation_year,
                'volunteer_id' => $this->volunteer_id
            ]
        );
    
        return $status;
    }

    public function getVolunteerId(): int
    {
        return $this->volunteer_id;
    }

    public function getGraduationYear(): string
    {
        return $this->graduation_year;
    }

}
?>
