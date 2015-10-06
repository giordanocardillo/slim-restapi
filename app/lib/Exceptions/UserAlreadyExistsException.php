<?php

class UserAlreadyExistsException extends Exception {
	protected $message = "User already exists";
}