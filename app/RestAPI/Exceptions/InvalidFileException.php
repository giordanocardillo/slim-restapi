<?php

namespace RestAPI\Exceptions;

use RestAPI\Utils\HttpCodes;

class InvalidFileException extends FileUploadException {
  protected $code = HttpCodes::BAD_REQUEST;
}
