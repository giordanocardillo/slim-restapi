<?php

namespace RestAPI\Utils;

use Exception;
use \Slim\Http\Response as SlimResponse;

class ErrorResponse {

  public function __construct(SlimResponse $response, Exception $exception, $statusCode = HttpCodes::INTERNAL_SERVER_ERROR, $debug = NULL) {

    switch ($statusCode) {
      case HttpCodes::BAD_REQUEST:
        break;
      case HttpCodes::UNATHORIZED:
        break;
      case HttpCodes::FORBIDDEN:
        break;
      case HttpCodes::NOT_FOUND:
        break;
      case HttpCodes::INTERNAL_SERVER_ERROR:
        break;
      case HttpCodes::METHOD_NOT_ALLOWED:
        break;
      default:
        $statusCode = HttpCodes::INTERNAL_SERVER_ERROR;
        break;

    }

    $data = [
      "message" => $exception->getMessage(),
      "errorClass" => get_class($exception)
    ];

    if ($statusCode == HttpCodes::INTERNAL_SERVER_ERROR) {
      $data['trace'] = $exception->getTraceAsString();
    }

    if (isset($debug)) {
      $data['debug'] = var_export($debug, true);
    }

    return $response->withJson($data, $statusCode);
  }

}
