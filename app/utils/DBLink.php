<?php

class DBLink extends PDO {

	private $db_driver = "mysql";
	//FIXME Host
	private $db_host = "";
	//FIXME DB Name
	private $db_name = "";
	//FIXME DB User
	private $db_username = "";
	//FIXME Password
	private $db_passwd = "";

	public function __construct() {
		$dsn = "$this->db_driver:host=$this->db_host;dbname=$this->db_name";
		return parent::__construct($dsn, $this->db_username, $this->db_passwd, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	}
}