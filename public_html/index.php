<?php

use Monolog\Formatter\LineFormatter;
use RestAPI\Exceptions\APINotFoundException;
use RestAPI\Exceptions\MethodNotAllowedException;
use RestAPI\Utils\APIResponse;
use RestAPI\Utils\ConfigurationManager;
use RestAPI\Utils\DBProvider;
use RestAPI\Utils\HttpCodes;
use Slim\Http\Request as SlimRequest;
use Slim\Http\Response as SlimResponse;

/* Global project folders */
define("APP_DIR", __DIR__ . '/../app');
define("ROUTES_DIR", __DIR__ . '/../app/routes');
define("LOGS_DIR", __DIR__ . '/../app/logs');
define("PUBLIC_DIR", __DIR__);
define("IMAGES_DIR", __DIR__ . "/images");

/* Remove errors */
ini_set("display_errors", "0");

/* Include autoloader */
require __DIR__ . '/../vendor/autoload.php';

/* Global Configuration */
$appConfiguration = ConfigurationManager::getInstance()->getApp();
define("APP_NAME", $appConfiguration->name);
define("USE_DB", $appConfiguration->useDatabase);
define("USE_LOG", $appConfiguration->useLog);
define("DEBUG", $appConfiguration->debugMode);
define("LOG_FILE_NAME", APP_NAME . ".log");

if (DEBUG) {
  define("LOG_LEVEL", Monolog\Logger::DEBUG);
} else {
  define("LOG_LEVEL", Monolog\Logger::WARNING);
}

/* Global app object */
$app = new \Slim\App();

/* Slim container */
$c = $app->getContainer();

/* Debug setting */
$settings = $c->get('settings');
$settings->replace([
  'displayErrorDetails' => DEBUG,
  'debug' => DEBUG,
]);

$c['logger'] = function ($c) {
  $logger = new \Monolog\Logger(APP_NAME);
  if (USE_LOG) {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
      $ip = $_SERVER['REMOTE_ADDR'];
    }
    $fileHandler = new \Monolog\Handler\RotatingFileHandler(LOGS_DIR . "/" . LOG_FILE_NAME, 2, LOG_LEVEL);
    $fileHandler->setFormatter(new LineFormatter("[%datetime%] [$ip] %level_name%: %message% %context%\n"));
    $logger->pushHandler($fileHandler);
  } else {
    $nullHandler = new \Monolog\Handler\NullHandler();
    $logger->pushHandler($nullHandler);
  }
  return $logger;
};

/* Setting error handling */
$c['errorHandler'] = function ($c) {
  return function (SlimRequest $request, SlimResponse $response, Exception $exception) use ($c) {
    /** @var \Slim\Container $c */
    $c->logger->addError((string)$exception->getMessage(), [$request->getMethod(), (string)$request->getUri()]);
    return APIResponse::withError($response, $exception);
  };
};

/* Setting not found handling */
$c['notFoundHandler'] = function ($c) {
  return function (SlimRequest $request, SlimResponse $response) use ($c) {
    /** @var \Slim\Container $c */
    if ($request->getMethod() == "OPTIONS") {
      return APIResponse::withSuccess($response);
    }
    $c->logger->addWarning("API not found", [$request->getMethod(), (string)$request->getUri()]);
    return APIResponse::withError($response, new APINotFoundException("API not found"), HttpCodes::NOT_FOUND);
  };
};

/* Setting not allowed handling */
$c['notAllowedHandler'] = function ($c) {
  return function (SlimRequest $request, SlimResponse $response, $methods) use ($c) {
    /** @var \Slim\Container $c */
    if ($request->getMethod() == "OPTIONS") {
      return APIResponse::withSuccess($response);
    }
    $allowedMethods = implode(', ', $methods);
    $c->logger->addWarning("Method not allowed (only $allowedMethods)", [$request->getMethod(), (string)$request->getUri()]);
    $newResponse = $response->withHeader("Allow", $allowedMethods);
    return APIResponse::withError($newResponse, new MethodNotAllowedException("Allowed methods: $allowedMethods"), HttpCodes::METHOD_NOT_ALLOWED);
  };
};

/* Database connection */
if (USE_DB) {
  $c['dbProvider'] = function ($c) {
    return new DBProvider();
  };
}

/* Routes requires */
foreach (glob(ROUTES_DIR . "/*.php") as $file) {
  require_once $file;
}

$app->run();

