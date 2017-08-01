<?php

namespace RestAPI\Utils;

use \Slim\Http\Response as SlimResponse;

class SuccessResponse extends SlimResponse {

  public function __construct(SlimResponse $response, $status = HttpCodes::OK, $data = "success") {
    parent::__construct($status);
    $status = self::filterStatus($status);
    return $response->withJson($data, $status);
  }

  protected function filterStatus($status) {
    switch ($status) {
      case HttpCodes::OK:
        break;
      case HttpCodes::PARTIAL_CONTENT:
        break;
      default:
        $status = HttpCodes::OK;
        break;
    }

    return $status;
  }

}
