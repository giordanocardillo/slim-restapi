<?php
class UnauthorizedException extends InvalidArgumentException {
	protected $message = "Not authorized";
}