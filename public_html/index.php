<?php

/* Cartelle di progetto*/
define("APP", __DIR__ . '/../app');
define("ROUTES", __DIR__ . '/../app/routes');
define("PUBLIC", __DIR__);
define("IMAGES", __DIR__ . "/images");

/* Inclusione autoloaders */
require APP . '/vendor/Slim/Slim.php';
require APP . '/autoloaders/LibsAutoloader.php';
require APP . '/autoloaders/UtilsAutoloader.php';
require APP . '/autoloaders/ViewsAutoloader.php';

/* Registrazione autoloaders */
\Slim\Slim::registerAutoloader();
LibsAutoloader::registerAutoloader();
UtilsAutoloader::registerAutoloader();
ViewsAutoloader::registerAutoloader();

/* Oggetto DB Globale */
$db = new DBLink();

/* Oggetto FluentPDO globale */
$fp = new FluentPDO($db);

/* Oggetto App Globale */
$app = new \Slim\Slim();

/* Impostazione template di rendering globale a JSON */
$app->view(new JSONView());

/* Impostazione error handling */
$app->error(function (Exception $e) use ($app) {
	$app->render(new ErrorResponse($e));
	$app->stop();
});



/* Impostazione not found handling */
$app->notFound(function () use ($app) {
	if ($app->request->getMethod() == \Slim\Http\Request::METHOD_OPTIONS) {
		$app->render(new SuccessResponse());
		$app->stop();
	}
	$app->render(new ErrorResponse(new Exception("Not implemented"), Response::HTTP_NOT_FOUND));
	$app->stop();

});

/* Qui vengono inseriti tutti i require delle routes */
require ROUTES . "/session.php";

/* Route di default che mostra una pagina statica */
$app->get("/", function () {
	echo <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>RESTful API</title>
	<link rel="icon" type="image/png" href="favicon.ico">
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

});

$app->run();
