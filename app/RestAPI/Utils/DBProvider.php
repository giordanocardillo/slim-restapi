<?php

namespace RestAPI\Utils;

use PDO;
use RestAPI\Exceptions\ConfigurationNotExistsException;

class DBProvider {

  private $databases;

  /**
   * @throws ConfigurationNotExistsException
   */
  public function __construct() {
    $this->databases = ConfigurationManager::getInstance()->getDatabases();
  }

  /**
   * @throws ConfigurationNotExistsException
   */
  public function get($dbName) {
    if (!isset($this->databases->{$dbName})) {
      throw new ConfigurationNotExistsException("Configuration for $dbName does not exist");
    }

    $connectionDetails = $this->databases->{$dbName};

    $dsn = "$connectionDetails->driver:host=$connectionDetails->host;dbname=$connectionDetails->dbName";

    return new PDO($dsn, $connectionDetails->username, $connectionDetails->password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
  }


}
