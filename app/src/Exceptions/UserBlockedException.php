<?php

namespace RestAPI\Exceptions;

class UserBlockedException extends \Exception {
  protected $message = "User is blocked";
}
