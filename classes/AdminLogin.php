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
			$this->admin_id = "";
			$this->admin_name = "";
			$this->contribution_type = "";
		}

		public function setUsername(string $username): string
		{
			if(empty($username)) {
				return "Please enter your username.";
			}
			if($this->checkUsernameExists(strtolower($username)))
      			{
				$this->username = strtolower($username);
				return "";
			}
			else
			{
				return "No account found with that username.";
			}
		}

		private function checkUsernameExists($username): bool
		{
			$stmt = $this->pdo->prepare("SELECT 1 FROM admins WHERE username = :username");
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
			$sql = "SELECT admin_id, admin_name, username, password FROM admins WHERE username = :username";
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute(['username' => $this->username]);
			$admin = $stmt->fetch();

			if ($admin && password_verify($this->password, $admin['password']))
			{
				$this->admin_id = $admin["admin_id"];
				$this->admin_name = $admin["admin_name"];

				return true;
			} else 
			{
    				return false;
			}
		}

		public function getAdminId(): int
		{
			return $this->admin_id;
		}

		public function getAdminName(): string
		{
			return $this->admin_name;
		}


}
?>
