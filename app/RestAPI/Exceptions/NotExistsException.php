<?php

namespace RestAPI\Exceptions;

use RestAPI\Utils\HttpCodes;

class NotExistsException extends \Exception {
  protected $code = HttpCodes::NOT_FOUND;
}
