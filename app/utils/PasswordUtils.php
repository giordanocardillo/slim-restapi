<?php

class PasswordUtils {

	public static function generate($passwordLength = 12, $charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789.-+=_,!@$#*%<>[]{}") {
		$password = "";
		for ($i = 0; $i < $passwordLength; $i++) {
			$password .= $charset[mt_rand(0, strlen($charset) - 1)];
		}
		return $password;
	}

	public static function hash($password) {
		return password_hash($password, PASSWORD_DEFAULT);
	}

	public static function verify($password, $hash) {
		if (!password_verify($password, $hash)) {
			throw new UnauthorizedException();
		}
	}

}