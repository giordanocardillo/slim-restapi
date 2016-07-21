<?php
use Slim\Http\Request as SlimRequest;
use Slim\Http\Response as SlimResponse;

// Check della sessione utente
$app->get('/session/check', function (SlimRequest $request, SlimResponse $response) use ($dbs, $fps) {
    $data = array();
    try {

        $session_payload = SessionManager::checkSession($request);
        $data['session'] = "valid";
        $data['expiresIn'] = $session_payload->exp - time();

        return $response->withJson(new SuccessResponse($data));

    } catch (Exception $e) {
        return $response->withJson(new ErrorResponse($e, Response::HTTP_UNATHORIZED), Response::HTTP_UNATHORIZED);
    }
});
