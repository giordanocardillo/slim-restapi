<?php

namespace RestAPI\Exceptions;

use RestAPI\Utils\HttpCodes;

class InvalidTokenException extends \Exception {
  protected $message = "Token is invalid";
  protected $code = HttpCodes::FORBIDDEN;
}
