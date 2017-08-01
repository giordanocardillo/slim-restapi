<?php

namespace RestAPI\Utils;

use Exception;
use \Slim\Http\Response as SlimResponse;

class ErrorResponse extends SlimResponse {

  public function __construct(SlimResponse $response, $status = HttpCodes::INTERNAL_SERVER_ERROR, Exception $exception, $debug = null) {
    parent::__construct($status);
    $status = self::filterStatus($status);

    $data = [
      "error" => $exception->getMessage(),
      "errorClass" => get_class($exception)
    ];

    if ($status == HttpCodes::INTERNAL_SERVER_ERROR) {
      $data['trace'] = $exception->getTraceAsString();
    }

    if (isset($debug) && (defined(DEBUG) && DEBUG)) {
      $data['debug'] = var_export($debug, true);
    }

    return $response->withJson($data, $status);
  }

  protected function filterStatus($status) {
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

    return $status;
  }

}
