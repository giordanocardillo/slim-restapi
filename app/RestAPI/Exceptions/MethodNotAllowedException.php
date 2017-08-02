<?php

namespace RestAPI\Exceptions;

use RestAPI\Utils\HttpCodes;

class MethodNotAllowedException extends \Exception {
  protected $code = HttpCodes::METHOD_NOT_ALLOWED;
}
