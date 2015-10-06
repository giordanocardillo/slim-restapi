<?php

class UserNotExistsException extends NotExistsException {
	protected $message = "User not exists";
}