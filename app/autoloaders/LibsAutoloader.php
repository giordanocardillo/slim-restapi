<?php

class LibsAutoloader {

	public static function autoload($className) {
		$ds = DIRECTORY_SEPARATOR;
		if (preg_match("/[a-zA-Z]+Exception$/", $className)) {
			$fileName = __DIR__ . $ds . ".." . $ds . "lib" . $ds . "Exceptions" . $ds . $className . ".php";
			if (file_exists($fileName)) {
				require $fileName;
				return true;
			}
		} else {
			$fileName = __DIR__ . $ds . ".." . $ds . "lib" . $ds . $className . ".php";
			if (file_exists($fileName)) {
				require $fileName;
				return true;
			}
		}
		return false;
	}


	public static function registerAutoloader() {
		spl_autoload_register(__CLASS__ . "::autoload");
	}

}