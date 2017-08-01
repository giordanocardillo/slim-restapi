<?php

namespace RestAPI\Utils;

class Response {

  const HTTP_OK = 200;
  const HTTP_PARTIAL_CONTENT = 206;
  const HTTP_BAD_REQUEST = 400;
  const HTTP_UNATHORIZED = 401;
  const HTTP_FORBIDDEN = 403;
  const HTTP_NOT_FOUND = 404;
  const HTTP_NOT_ALLOWED = 405;
  const HTTP_INTERNAL_SERVER_ERROR = 500;


  public function __construct($data, $status) {
    if (is_array($data)) {
      $data = $this->camelCaseKeys($data);
    }
    if ($status == "error") {

      $this->error = $data;
    } else {
      if (!empty($data)) {
        $this->data = $data;
      }
    }
    $this->status = $status;
  }

  private function camelCaseKeys($array, $arrayHolder = array()) {

    $camelCaseArray = !empty($arrayHolder) ? $arrayHolder : array();

    foreach ($array as $key => $val) {
      $newKey = @explode('_', $key);
      array_walk($newKey, create_function('&$v', '$v = ucwords($v);'));
      $newKey = @implode('', $newKey);
      $newKey{0} = strtolower($newKey{0});

      if (is_object($val)) {
        $val = (array)$val;
      }

      if (!is_array($val)) {
        $camelCaseArray[$newKey] = $val;
      } else {
        $camelCaseArray[$newKey] = self::camelCaseKeys($val, $camelCaseArray[$newKey]);
      }
    }
    return $camelCaseArray;
  }

}
