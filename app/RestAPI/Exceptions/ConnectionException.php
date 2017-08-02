<?php

namespace RestAPI\Exceptions;

use RestAPI\Utils\HttpCodes;

class ConnectionException extends \HttpException {
  protected $code = HttpCodes::INTERNAL_SERVER_ERROR;
}
