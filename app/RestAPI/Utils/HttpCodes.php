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

    $reflect = new \ReflectionClass(__CLASS__);

    $validCodes = array_filter($reflect->getConstants(), function ($code) {
      return $code >= 400;
    });

    return in_array($statusCode, $validCodes);

  }

  public static function isValidSuccessCode($statusCode) {

    $reflect = new \ReflectionClass(__CLASS__);

    $validCodes = array_filter($reflect->getConstants(), function ($code) {
      return ($code < 300 && $code >= 200);
    });

    return in_array($statusCode, $validCodes);

  }

}
