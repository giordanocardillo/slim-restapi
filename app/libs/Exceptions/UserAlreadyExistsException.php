<?php

namespace RestAPI\Exceptions;

class UserAlreadyExistsException extends \Exception {
  protected $message = "User already exists";
}
