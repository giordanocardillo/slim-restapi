<?php

/* Default route */

use RestAPI\Utils\APIResponse;
use Slim\Http\Request as SlimRequest;
use Slim\Http\Response as SlimResponse;

$app->get("/", function (SlimRequest $request, SlimResponse $response) {

  $this->logger->addInfo("Welcome to " . APP_NAME);
  return APIResponse::withSuccess($response, APP_NAME);
});
