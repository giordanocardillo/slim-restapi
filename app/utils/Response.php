<?php

class Response {

    const HTTP_OK = 200;
    const HTTP_PARTIAL_CONTENT = 206;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_UNATHORIZED = 401;
    const HTTP_FORBIDDEN = 403;
    const HTTP_NOT_FOUND = 404;
    const HTTP_INTERNAL_SERVER_ERROR = 500;

    public $data;
    public $status_code;
    public $status_text;

    public function __construct($data, $status_code = self::HTTP_OK) {

        switch ($status_code) {
            case self::HTTP_OK:
                $this->status_text = "OK";
                break;
            case self::HTTP_PARTIAL_CONTENT:
                $this->status_text = "Partial Content";
                break;
            case self::HTTP_BAD_REQUEST:
                $this->status_text = "Bad Request";
                break;
            case self::HTTP_UNATHORIZED:
                $this->status_text = "Unauthorized";
                break;
            case self::HTTP_FORBIDDEN:
                $this->status_text = "Forbidden";
                break;
            case self::HTTP_NOT_FOUND:
                $this->status_text = "Not Found";
                break;
            case self::HTTP_INTERNAL_SERVER_ERROR:
                $this->status_text = "Internal Server Error";
                break;
            default:
                $this->status_code = self::HTTP_INTERNAL_SERVER_ERROR;
                $this->status_text = "Internal Server Error";
                $this->data['error']['message'] = "Invalid status code provided";
                break;

        }
        
        $this->data = $this->camelCaseKeys($data);
    }

    private function camelCaseKeys($array, $arrayHolder = array()) {

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

}