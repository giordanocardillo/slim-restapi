<?php

class ConnectionDetails {

	public $driver, $host, $dbName, $username, $password;

	public function __construct($driver, $host, $dbName, $username, $password) {
		$this->driver = $driver;
		$this->host = $host;
		$this->dbName = $dbName;
		$this->username = $username;
		$this->password = $password;
	}
}

class DBLink extends PDO {

	private $connections = [];

	public function __construct($connection) {

		$this->connections['db'] = new ConnectionDetails('mysql', '', '', '', '');

		if (!array_key_exists($connection, $this->connections)){
			throw  new Exception("Database configuration does not exist");
		}

		$connectionDetails = $this->connections[$connection];

		$dsn = "$connectionDetails->driver:host=$connectionDetails->host;dbname=$connectionDetails->dbName";
		return parent::__construct($dsn, $connectionDetails->username, $connectionDetails->password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	}
}