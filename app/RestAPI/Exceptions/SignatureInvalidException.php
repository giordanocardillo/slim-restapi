<?php

namespace RestAPI\Exceptions;

use RestAPI\Utils\HttpCodes;

class SignatureInvalidException extends \UnexpectedValueException {
  protected $code = HttpCodes::UNATHORIZED;

}
