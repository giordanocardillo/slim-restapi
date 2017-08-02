<?php

namespace RestAPI\Exceptions;

use RestAPI\Utils\HttpCodes;

class FileUploadException extends \Exception {
  protected $message = "File not uploaded";
  protected $code = HttpCodes::INTERNAL_SERVER_ERROR;

}
