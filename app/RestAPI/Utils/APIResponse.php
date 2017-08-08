<?php

namespace RestAPI\Utils;

use Exception;
use ReflectionClass;
use \Slim\Http\Response as SlimResponse;

class APIResponse {

  public static function withError(SlimResponse $response, Exception $exception, $status = null, $debug = null) {


    if (!HttpCodes::isValidErrorCode($status)) {
      if (HttpCodes::isValidErrorCode($exception->getCode())) {
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

  public static function withSuccess(SlimResponse $response, $data = "success", $status = HttpCodes::OK) {

    if (!HttpCodes::isValidSuccessCode($status)) {
      $status = $status = HttpCodes::OK;
    }

    return $response->withJson(["data" => $data], $status);
  }

  private static function camelCaseKeys($array, $arrayHolder = array()) {

    $camelCaseArray = !empty($arrayHolder) ? $arrayHolder : array();

    foreach ($array as $key => $val) {
      $newKey = @explode('_', $key);
      array_walk($newKey, create_function('&$v', '$v = ucwords($v);'));
      $newKey = @implode('', $newKey);
      $newKey{0} = strtolower($newKey{0});

      if (is_object($val)) {
        $val = (array)$val;
      }

      $camelCaseArray[$newKey] = $val;

      if (is_array($val)) {
        $camelCaseArray[$newKey] = self::camelCaseKeys($val, $camelCaseArray[$newKey]);
      }

    }
    return $camelCaseArray;
  }

}
