<?php

namespace RestAPI\Utils;


use RestAPI\Exceptions\ConfigurationNotExistsException;

class ConfigurationManager {

  private $configuration;

  /**
   * @throws ConfigurationNotExistsException
   */
  private function __construct() {
    if (!file_exists(APP_DIR . "/config.json")) {
      throw new ConfigurationNotExistsException("Configuration file does not exist");
    }

    $configuration = json_decode(file_get_contents(APP_DIR . "/config.json"));

    if (!isset($configuration->app)) {
      throw  new ConfigurationNotExistsException("App configuration does not exist");
    }

    $this->configuration = $configuration;

  }

  /**
   * @throws ConfigurationNotExistsException
   */
  public static function getInstance() {
    static $instance = null;
    if ($instance == null) {
      $instance = new self();
    }

    return $instance;
  }

  /**
   * @throws ConfigurationNotExistsException
   */
  public function getSession() {


    if (!isset($this->configuration->session)) {
      throw  new ConfigurationNotExistsException("Session configuration does not exist");
    }

    if (!isset($this->configuration->session->expireMinutes)) {
      throw  new ConfigurationNotExistsException("Session expire time configuration does not exist");
    }

    if (!isset($this->configuration->session->JWTKeys)) {
      throw  new ConfigurationNotExistsException("Session JWT Keys configuration does not exist");
    }

    return $this->configuration->session;
  }

  /**
   * @throws ConfigurationNotExistsException
   */
  public function getDatabases() {

    if (!isset($this->configuration->databases)) {
      throw  new ConfigurationNotExistsException("Databases configuration does not exist");
    }

    return $this->configuration->databases;
  }

  public function getApp() {
    return $this->configuration->app;
  }

  public function isDebug() {
    return $this->configuration->app->debugMode;
  }

}
