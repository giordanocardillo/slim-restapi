<?php

namespace RestAPI\Utils;

use PDO;
use Exception;

class DBLink extends PDO {

  /**
   * DBLink constructor.
   * @param $dbName "The connection name"
   * @throws Exception
   */
  public function __construct($dbName) {

    // Getting DB configurations
    if (!file_exists(APP . "/db.config.json")) {
      throw new Exception("Database configuration file does not exist");
    }

    $configurations = json_decode(file_get_contents(APP . "/db.config.json"));

    if (!isset($configurations->{$dbName})) {
      throw  new Exception("Database configuration does not exist");
    }

    $connectionDetails = $configurations->{$dbName};

    $dsn = "$connectionDetails->driver:host=$connectionDetails->host;dbname=$connectionDetails->dbName";
    return parent::__construct($dsn, $connectionDetails->username, $connectionDetails->password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
  }

  /**
   * @return DBLink[]
   * @throws Exception
   */
  public static function connectAll() {

    $pdos = [];

    if (!file_exists(APP . "/db.config.json")) {
      throw new Exception("Database configuration file does not exist");
    }

    $configurations = json_decode(file_get_contents(APP . "/db.config.json"), true);

    foreach ($configurations as $db => $connectionDetails) {
      $pdos[$db] = new DBLink($db);
    }

    return $pdos;

  }
}
