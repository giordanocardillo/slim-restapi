<?php

namespace RestAPI\Exceptions;

use RestAPI\Utils\HttpCodes;

class APINotFoundException extends \Exception {
  protected $code = HttpCodes::NOT_FOUND;
}
