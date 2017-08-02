<?php

namespace RestAPI\Exceptions;

use RestAPI\Utils\HttpCodes;

class UserAlreadyExistsException extends \Exception {
  protected $message = "User already exists";
  protected $code = HttpCodes::BAD_REQUEST;

}
