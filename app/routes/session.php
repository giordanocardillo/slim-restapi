<?php

use RestAPI\Utils\ErrorResponse;
use RestAPI\Utils\HttpCodes;
use RestAPI\Utils\SuccessResponse;
use Slim\Http\Request as SlimRequest;
use Slim\Http\Response as SlimResponse;


// User session check
$app->get('/session/check', function (SlimRequest $request, SlimResponse $response)/* use ($DB)*/ {
  $data = [];
  try {

    $sessionPayload = SessionManager::checkSession($request);
    $data['session'] = "valid";
    $data['expiresIn'] = $sessionPayload->exp - time();

    return new SuccessResponse($response, $data);

  } catch (Exception $e) {
    return new ErrorResponse($response, $e, HttpCodes::UNATHORIZED);
  }
});

