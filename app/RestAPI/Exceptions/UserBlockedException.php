<?php

namespace RestAPI\Exceptions;

use RestAPI\Utils\HttpCodes;

class UserBlockedException extends \Exception {
  protected $message = "User is blocked";
  protected $code = HttpCodes::FORBIDDEN;
}
