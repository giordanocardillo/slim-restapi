<?php

use RestAPI\Utils\APIResponse;
use RestAPI\Utils\HttpCodes;
use RestAPI\Utils\SessionManager;
use Slim\Http\Request as SlimRequest;
use Slim\Http\Response as SlimResponse;


/* User session check */
$app->get('/session/check', function (SlimRequest $request, SlimResponse $response)/* use ($DB)*/ {
  $data = [];
  try {

    $sessionPayload = SessionManager::checkSession($request);
    $data['session'] = "valid";
    $data['expiresIn'] = $sessionPayload->exp - time();

    return APIResponse::withSuccess($response, $data);

  } catch (Exception $e) {
    return APIResponse::witherror($response, $e, HttpCodes::UNATHORIZED);
  }
});

