<?php

use Monolog\Formatter\LineFormatter;
use RestAPI\Exceptions\APINotFoundException;
use RestAPI\Exceptions\MethodNotAllowedException;
use RestAPI\Utils\APIResponse;
use RestAPI\Utils\DBLink;
use RestAPI\Utils\HttpCodes;
use Slim\Http\Request as SlimRequest;
use Slim\Http\Response as SlimResponse;

/* Global project folders */
define("APP", __DIR__ . '/../app');
define("ROUTES", __DIR__ . '/../app/routes');
define("LOGS", __DIR__ . '/../app/logs');
define("PUBLIC", __DIR__);
define("IMAGES", __DIR__ . "/images");

/* Include autoloader */
require APP . '/vendor/autoload.php';

/* Global Configuration */
define("APP_NAME", "RestAPI");
define("USE_DB", false);
define("USE_LOG", true);
define("LOG_LEVEL", Monolog\Logger::DEBUG);
define("LOG_FILE", LOGS . "/" . APP_NAME . ".log");
define("DEBUG", false);


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
    $fileHandler = new \Monolog\Handler\RotatingFileHandler(LOG_FILE, 2, LOG_LEVEL);
    $fileHandler->setFormatter(new LineFormatter("[%datetime%] %channel%.%level_name%: %message% %context%\n"));
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
  $c['db'] = function ($c) {
    /** @var \Slim\Container $c */
    return DBLink::connectAll();
  };
}

/* Routes requires */
foreach (glob(ROUTES . "/*.php") as $file) {
  require_once $file;
}

$app->run();

