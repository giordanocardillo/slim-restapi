<?php

namespace RestAPI\Exceptions;

use RestAPI\Utils\HttpCodes;

class FormatException extends \Exception {
  protected $code = HttpCodes::BAD_REQUEST;
}
