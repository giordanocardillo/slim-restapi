<?php

namespace RestAPI\Exceptions;

class UserNotExistsException extends NotExistsException {
  protected $message = "User not exists";
}
