<?php
require 'DatabaseConnection.php';

class VolunteerGoogleAuthentication
{
		protected $pdo = null;
		private $oauth_uid;
		private $oauth_provider;
		private $username;
		//private $password;
		private $first_name;
		private $last_name;
		private $volunteer_id;
		//private $graduation_year;

		public function __construct()
    {
			$this->pdo = (new DatabaseConnection)->getPDO();
			$this->oauth_uid = "";
			$this->oauth_provider = "google";
			$this->username = "";
			//$this->password = "";
			$this->first_name = "";
			$this->last_name = "";
			$this->volunteer_id = "";
			//$this->graduation_year = "";
		}

		public function setOAuthUId(string $oauth_uid): bool
		{
			if(empty($oauth_uid)) {
				return false;
			}
			else
			{
				$this->oauth_uid = $oauth_uid;
				return true;
			}
		}

		public function setUsername(string $username): bool
		{
			if(empty($username)) {
				return false;
			}
			else
      {
				$this->username = strtolower($username);
				return true;
			}
		}

		public function setFirstName(string $first_name): bool
		{
			if(empty($first_name)) {
				return false;
			}
			else
      {
				$this->first_name = $first_name;
				return true;
			}
		}

		public function setLastName(string $last_name): bool
		{
			if(empty($last_name)) {
				return false;
			}
			else
      {
				$this->last_name = $last_name;
				return true;
			}
		}


		private function checkUsernameExists($username): bool
		{
			$stmt = $this->pdo->prepare("SELECT 1 FROM volunteers WHERE username = :username");
			$stmt->execute(['username' => $username]);
			return (bool)$stmt->fetch();
		}

		public function authenticate(): bool
		{
			if($this->checkUsernameExists()){
				$this->login();
			}
			else {
				$status = $this->addVolunteer();
				if($status) {
					$this->volunteer_id = $this->getVolunteerIdFromLastInsert();
					return true;
				}
			}
		}

		public function login(): bool
		{
			$sql =
				"SELECT
					volunteer_id, username, first_name, last_name
				FROM
					volunteers
				WHERE
					oauth_uid = :oauth_uid
					AND oauth_provider = :oauth_provider";
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute(
				['oauth_uid' => $this->oauth_uid],
				['oauth_provider' => $this->oauth_provider]
			);
			$volunteer = $stmt->fetch();

			if ($volunteer)
			{
				$this->volunteer_id = $volunteer["volunteer_id"];
				$this->username = $volunteer["username"];
				$this->first_name = $volunteer["first_name"];
				$this->last_name = $volunteer["last_name"];
				// $this->graduation_year = $volunteer["graduation_year"];
				return true;
			} else {
    		return false;
			}
		}

		private function addVolunteer(): bool
		{
			$sql =
				"INSERT INTO
					volunteers (oauth_uid, oauth_provider, username, first_name, last_name)
				VALUES (:oauth_uid, :oauth_provider, :username, :first_name, :last_name)";
			$stmt = $this->pdo->prepare($sql);
			$status = $stmt->execute(
				[
					'oauth_uid' => $this->sponsor_name,
					'oauth_provider' => $this->username,
					'username' => $this->password,
					'first_name' => $this->contribution_type,
					'last_name' => $this->advisors[0]["name"],
				]
			);

			return $status;
		}

		private function getVolunteerIdFromLastInsert(): string
		{
			$stmt = $pdo->query("SELECT LAST_INSERT_ID()");
			return $stmt->fetch();
		}

		public function getVolunteerId(): int
		{
			return $this->volunteer_id;
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

		// public function getGraduationYear(): string
		// {
		// 	return $this->graduation_year;
		// }

}
?>
