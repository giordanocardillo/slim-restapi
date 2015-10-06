<?php

// Check della sessione utente
$app->get('/session/check', function () use ($app) {
	$data = array();
	try {
		$session_payload = SessionManager::checkSession($app->request->headers);
		$data['session'] = "valid";
		$data['expiresIn'] = $session_payload->exp - time();
		$app->render(new SuccessResponse($data));
	} catch (Exception $e) {
		$app->render(new ErrorResponse($e, Response::HTTP_UNATHORIZED));
	}
});
