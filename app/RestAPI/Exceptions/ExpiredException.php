<?php

namespace RestAPI\Exceptions;

use RestAPI\Utils\HttpCodes;

class ExpiredException extends \UnexpectedValueException {
  protected $code = HttpCodes::UNATHORIZED;
}
