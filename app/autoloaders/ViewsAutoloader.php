<?php

class ViewsAutoloader {

	private static function autoload($className) {
		$ds = DIRECTORY_SEPARATOR;
		$fileName = __DIR__ . $ds . ".." . $ds . "views" . $ds . $className . ".php";
		if (file_exists($fileName)) {
			require $fileName;
		}
		return true;
	}


	public static function registerAutoloader() {
		spl_autoload_register(__CLASS__ . "::autoload");
	}

}