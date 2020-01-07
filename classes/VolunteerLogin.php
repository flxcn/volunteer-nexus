<?php
require 'DatabaseConnection.php';

class VolunteerLogin
{
		protected $pdo = null;
		private $username;
		private $password;
		private $volunteer_id;
		private $first_name;
		private $last_name;
		private $graduation_year;

		public function __construct()
    {
			$this->pdo = (new DatabaseConnection)->getPDO();
      $this->username = "";
			$this->password = "";
			$this->volunteer_id = "";
			$this->first_name = "";
			$this->last_name = "";
			$this->graduation_year = "";
		}

		public function setUsername(string $username): string
		{
			if(empty($username)) {
				return "Please enter your email address.";
			}
			if($this->checkUsernameExists(strtolower($username)))
      {
				$this->username = strtolower($username);
				return "";
			}
			else
			{
				return "No account found with that email.";
			}
		}

		private function checkUsernameExists($username): bool
		{
			$stmt = $this->pdo->prepare("SELECT 1 FROM volunteers WHERE username = :username");
			$stmt->execute(['username' => $username]);
			return (bool)$stmt->fetch();
		}

		public function setPassword(string $password): string
		{
			if(empty($password)) {
				return "Please enter a password.";
			}
			else {
				$this->password = $password;
				return "";
			}
		}

		public function login(): bool
		{
			$sql =
				"SELECT
					volunteer_id,
					first_name,
					last_name,
					graduation_year,
					username,
					password
				FROM
					volunteers
				WHERE
					username = :username";
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute(['username' => $this->username]);
			$volunteer = $stmt->fetch();

			if ($volunteer && password_verify($this->password, $volunteer['password']))
			{
				$this->volunteer_id = $volunteer["volunteer_id"];
				$this->first_name = $volunteer["first_name"];
				$this->last_name = $volunteer["last_name"];
				$this->graduation_year = $volunteer["graduation_year"];
				return true;
			} else {
    		return false;
			}
		}

		public function getVolunteerId(): int
		{
			return $this->volunteer_id;
		}

		public function getFirstName(): string
		{
			return $this->first_name;
		}

		public function getLastName(): string
		{
			return $this->last_name;
		}

		public function getGraduationYear(): string
		{
			return $this->graduation_year;
		}

}
?>
