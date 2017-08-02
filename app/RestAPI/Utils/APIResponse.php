<?php

namespace RestAPI\Utils;

use Exception;
use \Slim\Http\Response as SlimResponse;

class APIResponse {

  public static function withError(SlimResponse $response, Exception $exception, $status = HttpCodes::INTERNAL_SERVER_ERROR, $debug = null) {

    switch ($status) {
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
        $status = HttpCodes::INTERNAL_SERVER_ERROR;
        break;
    }

    $data = [
      "error" => $exception->getMessage(),
      "errorClass" => get_class($exception)
    ];

    if ($status == HttpCodes::INTERNAL_SERVER_ERROR && (defined('DEBUG') && DEBUG)) {
      $data['trace'] = $exception->getTraceAsString();
    }

    if (isset($debug) && (defined('DEBUG') && DEBUG)) {
      $data['debug'] = var_export($debug, true);
    }

    return $response->withJson($data, $status);

  }

  public static function withSuccess(SlimResponse $response, $data = "success", $status = HttpCodes::OK) {

    switch ($status) {
      case HttpCodes::OK:
        break;
      case HttpCodes::PARTIAL_CONTENT:
        break;
      default:
        $status = HttpCodes::OK;
        break;
    }

    return $response->withJson(["data" => $data], $status);
  }

}
