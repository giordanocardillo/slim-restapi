<?php

namespace RestAPI\Utils;

use PDO;
use RestAPI\Exceptions\ConfigurationNotExistsException;

class DBLink extends PDO {

  /**
   * DBLink constructor.
   * @param $dbName "The connection name"
   * @throws ConfigurationNotExistsException
   */
  public function __construct($dbName) {

    $databases = ConfigurationManager::getInstance()->getDatabases();

    if (!isset($databases->{$dbName})) {
      throw  new ConfigurationNotExistsException("Configuration for $dbName does not exist");
    }

    $connectionDetails = $databases->{$dbName};

    $dsn = "$connectionDetails->driver:host=$connectionDetails->host;dbname=$connectionDetails->dbName";
    return parent::__construct($dsn, $connectionDetails->username, $connectionDetails->password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

  }

  /**
   * @return PDO[]
   */
  public static function connectAll() {

    $connections = [];

    $databases = ConfigurationManager::getInstance()->getDatabases();

    foreach ($databases as $db => $connectionDetails) {
      $dsn = "$connectionDetails->driver:host=$connectionDetails->host;dbname=$connectionDetails->dbName";
      $connections[$db] = new parent($dsn, $connectionDetails->username, $connectionDetails->password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    }

    return $connections;
  }
}
