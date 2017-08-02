<?php

namespace RestAPI\Exceptions;

use RestAPI\Utils\HttpCodes;

class ConfigurationNotExistsException extends \Exception {
  protected $code = HttpCodes::NOT_FOUND;
}
