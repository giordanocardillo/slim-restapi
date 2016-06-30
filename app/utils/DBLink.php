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

        // Getting DB configurations
        $this->connections = json_decode(file_get_contents(APP . "/db.config.json"));

        if (!isset($this->connections->{$connection})) {
            throw  new Exception("Database configuration does not exist");
        }

        $connectionDetails = $this->connections->{$connection};

        $dsn = "$connectionDetails->driver:host=$connectionDetails->host;dbname=$connectionDetails->dbName";
        return parent::__construct($dsn, $connectionDetails->username, $connectionDetails->password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    }
}