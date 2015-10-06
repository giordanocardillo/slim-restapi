<?php

class SuccessResponse extends Response {

	public function __construct($response = null, $status_code = Response::HTTP_OK) {

		switch ($status_code) {
			case Response::HTTP_OK:
				break;
			case Response::HTTP_PARTIAL_CONTENT:
				break;
			default:
				$status_code = Response::HTTP_OK;
				break;

		}

		$data = array("data" => $response);

		if (empty($response)) {
			unset($data['data']);
			$data['success'] = true;
		}

		return parent::__construct($data, $status_code);

	}

}