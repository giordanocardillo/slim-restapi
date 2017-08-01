<?php

/* Global project folders */
define("APP", __DIR__ . '/../app');
define("ROUTES", __DIR__ . '/../app/routes');
define("PUBLIC", __DIR__);
define("IMAGES", __DIR__ . "/images");

/* Include autoloader */
require APP . '/vendor/autoload.php';

/* Uses */

use RestAPI\Utils\DBLink;
use RestAPI\Utils\ErrorResponse;
use RestAPI\Utils\HttpCodes;
use RestAPI\Utils\SuccessResponse;
use Slim\Http\Request as SlimRequest;
use Slim\Http\Response as SlimResponse;

/* Global DBLink array */
/** @var DBLink[] $dbs */
$dbs = DBLink::connectAll();

/* global FluentPDO array */
/** @var FluentPDO[] $fps */
$fps = [];

foreach ($dbs as $connection => $link) {
  if (!is_a($link, 'DBLink')) {
    throw new BadFunctionCallException("Need only DBLink connections");
  }
  $fps->{$connection} = new FluentPDO($link);
}

/* Slim container */
$c = new \Slim\Container();

/* Setting error handling */
$c['errorHandler'] = function ($c) {
  return function (SlimRequest $request, SlimResponse $response, $exception) use ($c) {
    /** @var \Slim\Container $c */
    return new ErrorResponse($c['response'], $exception);
  };
};

/* Setting not found handling */
$c['notFoundHandler'] = function ($c) {
  return function (SlimRequest $request, SlimResponse $response) use ($c) {
    /** @var \Slim\Container $c */
    if ($request->getMethod() == "OPTIONS") {
      return new SuccessResponse($c['response']);
    }
    return new ErrorResponse($c['response'], new Exception("Not found"), HttpCodes::NOT_FOUND);
  };
};

/* Setting not allowed handling */
$c['notAllowedHandler'] = function ($c) {
  return function (SlimRequest $request, SlimResponse $response, $methods) use ($c) {
    /** @var \Slim\Container $c */
    if ($request->getMethod() == "OPTIONS") {
      return new SuccessResponse($c['response']);
    }
    return new ErrorResponse($c['response'], new Exception("Must be " . implode(', ', $methods)), HttpCodes::METHOD_NOT_ALLOWED);
  };
};

/* Global app object */
$app = new \Slim\App($c);

/* Routes requires */
foreach (glob(ROUTES . "/*.php") as $file) {
  require_once $file;
}

/* Default route */
$app->get("/", function (SlimRequest $request, SlimResponse $response) {
  $html = <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>RESTful API</title>
	<link href='https://fonts.googleapis.com/css?family=Raleway:400,500,600,700' rel='stylesheet' type='text/css'>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no">
	<style type="text/css">
		body{
			margin: 0;
			font-family: 'Raleway', sans-serif
		}
		#wrapper{
			height: 100%;
			width: 100%;
			position: absolute;
		}
		#box{
			border-radius: 5px;
			border: 2px solid rgb(0, 0, 0);
			background-color: rgba(0, 0, 0, 0.3);
			padding: 15px;
			max-width:400px;
			min-width: 320px;
			width:80%;
			max-height:250px;
			margin: -125px auto 0 auto;
			text-align: center;
			position:absolute;
			top: 50%;
			left: 0;
			right: 0
		}
		.text{
			color: rgba(0,0,0, 1);
			text-shadow: 2px 2px 5px rgba(44,55,53, 1);
		}
		.text p{
			font-size: 2rem; margin: 10px 0 0 0
		}
	</style>
</head>
<body>
<div id="wrapper">
	<div id="box">
		<div class="text">
			<p>RESTful API</p>
		</div>
	</div>
</div>
</body>
</html>
HTML;
  return $response->getBody()->write($html);
});

$app->run();
