<?php
namespace Connector;

abstract class Database {

        protected $host;
        protected $username;
        protected $password;
        protected $conn;

	protected $configLocation = 'database_config.json';

        public function setCreds($creds) {
                $this->host = $creds['host'];
                $this->username = $creds['username'];
                $this->password = $creds['password'];
        }

        protected function loadCredsFromConfig($connectionType) {
                if (file_exists($this->configLocation)) {
                        $json = json_decode(file_get_contents($this->configLocation), true);
                } else {
                        exit('database config file not found');
                }
		return $json[$connectionType];
        }
}

interface databaseConnection {
	public function __construct($databaseName);	
	public function execQuery($queryString);
}

class MySQLConnection extends Database implements databaseConnection {

	protected $connectionType = 'mysql';

	public function __construct($databaseName) {
		parent::setCreds(parent::loadCredsFromConfig($this->connectionType));
		$this->conn = new \MySQLi($this->host, $this->username, $this->password, $databaseName);
		// Check connection
		if ($this->conn->connect_error) {
		    die('Connection failed: ' . $conn->connect_error);
		}
		echo 'Connected successfully';
	}

	public function execQuery($queryString) {
		$result = $this->conn->query($queryString);
		$returnRows = Array();
                 while($row = $result->fetch_assoc()) {
			array_push($returnRows, $row);
		}
		return $returnRows;
	}

	public function __destruct() {
		$this->conn->close();
	}
}


class PGConnection extends database implements databaseConnection {

	private $connectionType = 'postgres';

	public function __construct($databaseName) {
		parent::setCreds(parent::loadCredsFromConfig($this->connectionType));
		$this->conn = pg_connect("host = $this->host user = $this->username password = $this->password dbname = $databaseName") or die('Could not connect: ' . pg_last_error());
		echo 'Connected successfully';
	}

	public function execQuery($queryString) {
		$result = pg_query($this->conn, $queryString) or die('Query failed: ' . pg_last_error());
		$returnRows = Array();
		while($row = pg_fetch_row($result)) {
			array_push($returnRows, $row);
		}
		return $returnRows;
	}

	public function __destruct() {
		pg_close($this->conn);
	}
}

$theCats = (new MySQLConnection('mysql_database'))->execQuery('select * from cats');
var_dump($theCats);

$thePeople = (new PGConnection('postgres_database'))->execQuery('select * from people');
var_dump($thePeople);
