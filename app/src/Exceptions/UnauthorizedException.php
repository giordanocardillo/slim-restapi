<?php

namespace RestAPI\Exceptions;

class UnauthorizedException extends \InvalidArgumentException {
  protected $message = "Not authorized";
}
