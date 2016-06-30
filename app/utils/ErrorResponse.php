<?php

class ErrorResponse extends Response {

    public function __construct(Exception $exception, $status_code = Response::HTTP_INTERNAL_SERVER_ERROR, $debug = NULL) {

        switch ($status_code) {
            case Response::HTTP_BAD_REQUEST:
                break;
            case Response::HTTP_UNATHORIZED:
                break;
            case Response::HTTP_FORBIDDEN:
                break;
            case Response::HTTP_NOT_FOUND:
                break;
            case Response::HTTP_INTERNAL_SERVER_ERROR:
                break;
            case Response::HTTP_NOT_ALLOWED:
                break;
            default:
                $status_code = Response::HTTP_INTERNAL_SERVER_ERROR;
                break;

        }

        $data = [
            "message" => $exception->getMessage(),
            "errorClass" => get_class($exception)
        ];

        if ($status_code == Response::HTTP_INTERNAL_SERVER_ERROR) {
            $data['trace'] = $exception->getTraceAsString();
        }

        if (isset($debug)) {
            $data['debug'] = $debug;
        }

        return parent::__construct($data, "error");

    }

}