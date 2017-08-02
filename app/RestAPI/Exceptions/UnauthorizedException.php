<?php

namespace RestAPI\Exceptions;

use RestAPI\Utils\HttpCodes;

class UnauthorizedException extends \InvalidArgumentException {
  protected $message = "Not authorized";
  protected $code = HttpCodes::UNATHORIZED;

}
