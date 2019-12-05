<?php
class SponsorRegistration
{
    protected $pdo = null;
		private $sponsor_name;
		private $username;
		private $password;
		private $confirm_password;
		private $contribution_type;
		private $advisors;

		public function __construct()
    {
      $options = [
			    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			    PDO::ATTR_EMULATE_PREPARES   => false,
			];
      $this->pdo = new PDO("mysql:host=localhost;dbname=volunteer_nexus;charset=utf8mb4", "root", "root", $options);
			$this->sponsor_name = "";
			$this->username = "";
			$this->password = "";
			$this->confirm_password = "";
			$this->contribution_type = "";
			$this->advisors = array();
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

    public function addSponsor(): bool
    {
			$sql = "INSERT INTO sponsors (sponsor_name, username, password, contribution_type, advisor1_name, advisor1_email, advisor1_phone, advisor2_name, advisor2_email, advisor2_phone, advisor3_name, advisor3_email, advisor3_phone)
				VALUES (:sponsor_name, :username, :password, :contribution_type, :advisor1_name, :advisor1_email, :advisor1_phone, :advisor2_name, :advisor2_email, :advisor2_phone, :advisor3_name, :advisor3_email, :advisor3_phone)";
			$stmt = $this->pdo->prepare($sql);
			$status = $stmt->execute(
				[
					'sponsor_name' => $this->sponsor_name,
					'username' => $this->username,
					'password' => $this->password,
					'contribution_type' => $this->contribution_type,
					'advisor1_name' => $this->advisors[0]["name"],
					'advisor1_email' => $this->advisors[0]["email"],
					'advisor1_phone' => $this->advisors[0]["phone"],
					'advisor2_name' => $this->advisors[1]["name"],
					'advisor2_email' => $this->advisors[1]["email"],
					'advisor2_phone' => $this->advisors[1]["phone"],
					'advisor3_name' => $this->advisors[2]["name"],
					'advisor3_email' => $this->advisors[2]["email"],
					'advisor3_phone' => $this->advisors[2]["phone"],
				]);

      return $status;
    }
}
?>
