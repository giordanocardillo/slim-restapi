<?php

namespace RestAPI\Utils;

use \Slim\Http\Response as SlimResponse;

class SuccessResponse {

  public function __construct(SlimResponse $response, $data = null, $statusCode = HttpCodes::OK) {

    switch ($statusCode) {
      case HttpCodes::OK:
        break;
      case HttpCodes::PARTIAL_CONTENT:
        break;
      default:
        $statusCode = HttpCodes::OK;
        break;

    }

    return $response->withJson($data, $statusCode);
  }

}
