<?php
require_once 'DatabaseConnection.php';

class AdminLogin
{
		protected $pdo = null;
		private $username;
		private $password;
		private $admin_id;
		private $admin_name;
		private $contribution_type;

		public function __construct()
    {
			$this->pdo = DatabaseConnection::instance();
      $this->username = "";
			$this->password = "";
			$this->sponsor_id = "";
			$this->sponsor_name = "";
			$this->contribution_type = "";
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
			$stmt = $this->pdo->prepare("SELECT 1 FROM sponsors WHERE username = :username");
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
			$sql = "SELECT sponsor_id, sponsor_name, contribution_type, username, password FROM sponsors WHERE username = :username";
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute(['username' => $this->username]);
			$sponsor = $stmt->fetch();

			if ($sponsor && password_verify($this->password, $sponsor['password']))
			{
				$this->sponsor_id = $sponsor["sponsor_id"];
				$this->sponsor_name = $sponsor["sponsor_name"];
				$this->contribution_type = $sponsor["contribution_type"];
				return true;
			} else {
    		return false;
			}
		}

		public function getSponsorId(): int
		{
			return $this->sponsor_id;
		}

		public function getSponsorName(): string
		{
			return $this->sponsor_name;
		}

		public function getContributionType(): string
		{
			return $this->contribution_type;
		}

}
?>
