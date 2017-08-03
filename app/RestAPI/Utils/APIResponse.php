<?php

namespace RestAPI\Utils;

use Exception;
use ReflectionClass;
use \Slim\Http\Response as SlimResponse;

class APIResponse {

  public static function withError(SlimResponse $response, Exception $exception, $status = null, $debug = null) {

    if (!self::isValidErrorCode($status)) {
      if (self::isValidErrorCode($exception->getCode())) {
        $status = $exception->getCode();
      }
    }

    if ($status == null) {
      $status = HttpCodes::INTERNAL_SERVER_ERROR;
    }

    $data = [
      "error" => $exception->getMessage(),
      "errorClass" => (new ReflectionClass($exception))->getShortName()
    ];

    if ($status == HttpCodes::INTERNAL_SERVER_ERROR && (defined('DEBUG') && DEBUG)) {
      $data['trace'] = $exception->getTraceAsString();
    }

    if (isset($debug) && (defined('DEBUG') && DEBUG)) {
      $data['debug'] = var_export($debug, true);
    }

    return $response->withJson($data, $status);

  }

  private static function isValidErrorCode($status) {

    $valid = true;

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
        $valid = false;
        break;
    }

    return $valid;

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
