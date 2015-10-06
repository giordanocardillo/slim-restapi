<?php

class JSONView extends \Slim\View {

	private static function camelCaseKeys($array, $arrayHolder = array()) {
		$camelCaseArray = !empty($arrayHolder) ? $arrayHolder : array();
		foreach ($array as $key => $val) {
			$newKey = @explode('_', $key);
			array_walk($newKey, create_function('&$v', '$v = ucwords($v);'));
			$newKey = @implode('', $newKey);
			$newKey{0} = strtolower($newKey{0});

			if (is_object($val)) {
				$val = (array)$val;
			}

			if (!is_array($val)) {
				$camelCaseArray[$newKey] = $val;
			} else {
				$camelCaseArray[$newKey] = self::camelCaseKeys($val, $camelCaseArray[$newKey]);
			}
		}
		return $camelCaseArray;
	}


	public function render($data) {
		$app = \Slim\Slim::getInstance();
		$response = $app->response();
		$response->header('Content-Type', 'application/json; charset=utf8');
		if (is_a($data, "Response")) {
			$response->setStatus($data->status_code);
			$response->body(json_encode(self::camelCaseKeys($data->data)));
		} elseif (is_array($data)) {
			$response->body(json_encode(self::camelCaseKeys($data)));
		} else {
			$response->body($data);
		}
	}


}