<?php

namespace RestAPI\Utils;

use RestAPI\Exceptions\FormatException;
use RestAPI\Exceptions\InvalidFileException;

class Validation {
  /**
   * @throws FormatException
   */
  public static function isValidEmailAddress($email) {

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      throw new FormatException("Invalid email");
    }
  }

  /**
   * @throws FormatException
   */
  public static function isValidGender($gender) {

    if ($gender != "male" and $gender != "female") {
      throw new FormatException("Invalid gender");
    }
  }

  /**
   * @throws FormatException
   */
  public static function isValidHumanName($name) {

    self::isValidString($name);

    if (!preg_match("/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$/u", $name)) {
      throw new FormatException("Invalid human name");
    }

  }

  /**
   * @throws FormatException
   */
  public static function isValidString($string, $min = 1, $max = 200) {

    if (empty($string) or strlen($string) <= $min or strlen($string) >= $max) {
      throw new FormatException("Invalid string");
    }
  }

  /**
   * @throws FormatException
   */
  public static function isValidUsername($username) {

    self::isValidString($username);

    if (!preg_match("/^[a-z0-9_\.-]{4,}$/i", $username)) {
      throw new FormatException("Invalid username");
    }

  }

  /**
   * @throws InvalidFileException
   */
  public static function isValidImageFile($fileName, $allowedTypes = array("image/png", "image/gif", "image/jpeg")) {

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $fileType = finfo_file($finfo, $fileName);
    finfo_close($finfo);

    if (!in_array($fileType, $allowedTypes)) {
      throw new InvalidFileException("File type not allowed");

    }
  }

  /**
   * @throws FormatException
   */
  public static function isValidDate($date) {
    $time = strtotime(str_replace('/', '-', $date));
    if ($time == false) {
      throw new FormatException("Invalid date");
    }

    return date("Y-m-d", $time);
  }


}
