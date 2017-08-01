<?php

namespace RestAPI\Utils;

use RestAPI\Exceptions\FormatException;
use RestAPI\Exceptions\InvalidFileException;

class Validation {
  public static function isValidEmailAddress($email) {

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      throw new FormatException("Invalid email");
    }
  }

  public static function isValidGender($gender) {

    if ($gender != "male" and $gender != "fermale") {
      throw new FormatException("Invalid gender");
    }
  }

  public static function isValidHumanName($name) {

    self::isValidString($name);

    if (!preg_match("/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$/u", $name)) {
      throw new FormatException("Invalid human name");
    }

  }

  public static function isValidString($string, $min = 1, $max = 200) {

    if (empty($string) or strlen($string) <= $min or strlen($string) >= $max) {
      throw new FormatException("Invalid string");
    }
  }

  public static function isValidUsername($username) {

    self::isValidString($username);

    if (!preg_match("/^[a-z0-9_\.-]{4,}$/i", $username)) {
      throw new FormatException("Invalid username");
    }

  }

  public static function isValidImageFile($file_name, $allowed_types = array("image/png", "image/gif", "image/jpeg")) {

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $file_type = finfo_file($finfo, $file_name);
    finfo_close($finfo);

    if (!in_array($file_type, $allowed_types)) {
      throw new InvalidFileException("File type not allowed");

    }
  }

  public static function isValidDate($date) {
    $time = strtotime(str_replace('/', '-', $date));
    if ($time == false) {
      throw new FormatException("Invalid date");
    }

    return date("Y-m-d", $time);
  }


}
