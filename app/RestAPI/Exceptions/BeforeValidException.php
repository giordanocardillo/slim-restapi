<?php

namespace RestAPI\Exceptions;

use RestAPI\Utils\HttpCodes;

class BeforeValidException extends \UnexpectedValueException {
  protected $code = HttpCodes::UNATHORIZED;
}
