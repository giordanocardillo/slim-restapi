<?php

namespace RestAPI\Utils;

abstract class HttpCodes {
  const OK = 200;
  const PARTIAL_CONTENT = 206;
  const BAD_REQUEST = 400;
  const UNATHORIZED = 401;
  const FORBIDDEN = 403;
  const NOT_FOUND = 404;
  const METHOD_NOT_ALLOWED = 405;
  const INTERNAL_SERVER_ERROR = 500;

  public static function isValidErrorCode($statusCode) {
    $errorCodes = [400, 401, 403, 404, 405, 500];
    return in_array($statusCode, $errorCodes);
  }

  public static function isValidSuccessCode($statusCode) {
    $successCodes = [200, 206];
    return in_array($statusCode, $successCodes);
  }

}
