<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
ini_set('log_errors',1);
error_reporting(E_ALL);

define('DB_HOST','localhost');
define('DB_NAME','volunteer_nexus');
define('DB_CHARSET','utf8mb4');
define('DB_USERNAME','root');
define('DB_PASSWORD','root');

class DatabaseConnection
{
    protected static $instance;
    protected $pdo;

    public function __construct() {
			$options = [
					PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
					PDO::ATTR_EMULATE_PREPARES   => false,
			];
      $dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset='.DB_CHARSET;
      $this->pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, $options);
    }

    public function getPDO()
		{
			return $this->pdo;
		}
}
?>
