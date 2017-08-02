<?php

namespace RestAPI\Exceptions;

use RestAPI\Utils\HttpCodes;

class UserNotExistsException extends NotExistsException {
  protected $message = "User not exists";
  protected $code = HttpCodes::NOT_FOUND;
}
