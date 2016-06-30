<?php

/* Cartelle di progetto*/
define("APP", __DIR__ . '/../app');
define("ROUTES", __DIR__ . '/../app/routes');
define("PUBLIC", __DIR__);
define("IMAGES", __DIR__ . "/images");

/* Inclusione autoloaders */
require __DIR__ . '/../app/vendor/autoload.php';
require __DIR__ . '/../app/autoloaders/LibsAutoloader.php';
require __DIR__ . '/../app/autoloaders/UtilsAutoloader.php';
require __DIR__ . '/../app/autoloaders/ViewsAutoloader.php';

/* Registrazione autoloaders */
LibsAutoloader::registerAutoloader();
UtilsAutoloader::registerAutoloader();
ViewsAutoloader::registerAutoloader();

/* Uses */
use Slim\Http\Request as SlimRequest;
use Slim\Http\Response as SlimResponse;

/* Oggetto DB Globale */
$db = new DBLink('db');

/* Oggetto FluentPDO globale */
$fp = new FluentPDO($db);

/* Container Slim */
$c = new \Slim\Container();

/* Impostazione error handling */
$c['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        return $c['response']->withJson(new ErrorResponse($exception));
    };
};

/* Impostazione not found handling */
$c['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        if ($request->getMethod() == "OPTIONS") {
            return $c['response']->withJson(new SuccessResponse());
        }
        return $c['response']->withJson(new ErrorResponse(new Exception("Not found")), Response::HTTP_NOT_FOUND);
    };
};

/* Oggetto App Globale */
$app = new \Slim\App($c);

/* Qui vengono inseriti tutti i require delle routes */
require ROUTES . "/session.php";

/* Route di default che mostra una pagina statica */
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
