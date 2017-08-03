<?php

use Firebase\JWT\ExpiredException;
use RestAPI\Exceptions\InvalidTokenException;
use RestAPI\Exceptions\UserNotExistsException;
use RestAPI\Utils\APIResponse;
use RestAPI\Utils\HttpCodes;
use RestAPI\Utils\Password;
use RestAPI\Utils\SessionManager;
use Slim\Http\Request as SlimRequest;
use Slim\Http\Response as SlimResponse;


$app->get('/session/check', function (SlimRequest $request, SlimResponse $response) {
  $data = [];
  try {

    $sessionPayload = SessionManager::checkSessionToken($request);
    $data['session'] = 'valid';
    $data['expires'] = $sessionPayload->exp;

    return APIResponse::withSuccess($response, $data);

  } catch (BadFunctionCallException $e) {
    return APIResponse::witherror($response, $e, HttpCodes::BAD_REQUEST);
  } catch (Exception $e) {
    return APIResponse::witherror($response, $e, HttpCodes::UNATHORIZED);
  }
});

$app->post('/session/refresh', function (SlimRequest $request, SlimResponse $response) {
  $data = [];

  // This variable is made for IDE hinting.
  // Embedding db in Slim container makes IDE lose track of the variable type
  /** @var PDO $db */
  $db = $this->dbs['db1'];

  try {

    $queryParams = $request->getQueryParams();

    // Check if username and refresh token is provided
    if (!isset($queryParams['refresh_token']) || empty($queryParams['refresh_token'])) {
      throw new BadFunctionCallException('Must provide refresh token');
    }

    // Check username against DB and get user ID and password
    $stmt = $db->prepare('SELECT user_id FROM refresh_tokens WHERE refresh_token = :refreshToken AND valid = 1');
    $stmt->bindParam(':refreshToken', $queryParams['refresh_token']);
    $stmt->execute();
    $refreshToken = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$refreshToken) {
      // If token does not exist or is invalid
      $this->logger->addWarning("Session refresh attempt using invalid or disabled token \"${queryParams['refresh_token']}\"");
      throw new InvalidTokenException();
    }

    // Issue new tokens
    $newSession = SessionManager::issueSession($refreshToken['user_id']);
    $newRefreshToken = SessionManager::issueRefreshToken();

    $db->beginTransaction();

    // Remove used refresh token
    $stmt = $db->prepare('DELETE FROM refresh_tokens WHERE refresh_token = :refreshToken');
    $stmt->bindParam(':refreshToken', $queryParams['refresh_token']);
    $stmt->execute();

    // Add new refresh token
    $stmt = $db->prepare('INSERT INTO refresh_tokens (refresh_token, user_id) VALUES (:refreshToken, :userId)');
    $stmt->bindParam(':refreshToken', $newRefreshToken);
    $stmt->bindParam(':userId', $refreshToken['user_id'], PDO::PARAM_INT);
    $stmt->execute();

    $db->commit();

    // Issue authentication token and return
    $data['token'] = $newSession['token'];
    $data['expires'] = $newSession['expires'];
    $data['refreshToken'] = $newRefreshToken;

    return APIResponse::withSuccess($response, $data);

  } catch (PDOException $e) {
    if ($db->inTransaction()) {
      $db->rollBack();
    }
    return APIResponse::witherror($response, $e, HttpCodes::INTERNAL_SERVER_ERROR);
  } catch (InvalidTokenException $e) {
    return APIResponse::witherror($response, $e, HttpCodes::FORBIDDEN);
  } catch (BadFunctionCallException $e) {
    return APIResponse::witherror($response, $e, HttpCodes::BAD_REQUEST);
  } catch (Exception $e) {
    return APIResponse::witherror($response, $e, HttpCodes::UNATHORIZED);
  }
});

$app->post('/session/login', function (SlimRequest $request, SlimResponse $response) {
  $data = [];

  // This variable is made for IDE hinting.
  // Embedding db in Slim container makes IDE lose track of the variable type
  /** @var PDO $db */
  $db = $this->dbs['db1'];

  try {
    $queryParams = $request->getQueryParams();

    // Check if username and password are provided
    if (!isset($queryParams['username']) || empty($queryParams['username'])) {
      throw new BadFunctionCallException('Must provide username');
    }

    if (!isset($queryParams['password']) || empty($queryParams['password'])) {
      throw new BadFunctionCallException('Must provide password');
    }

    // Check username against DB and get user ID and password
    $stmt = $db->prepare('SELECT id, password FROM users WHERE username = :username AND active = 1');
    $stmt->bindParam(':username', $queryParams['username']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
      // If user does not exist or is inactive
      $this->logger->addWarning("Login attempt using invalid or inactive username \"${queryParams['username']}\"");
      throw new UserNotExistsException();
    }

    // Verify password
    Password::verify($queryParams['password'], $user['password']);

    // Issue tokens
    $session = SessionManager::issueSession($user['id']);
    $refreshToken = SessionManager::issueRefreshToken();

    // Save the refresh token in DB for later usage
    $stmt = $db->prepare('INSERT INTO refresh_tokens (refresh_token, user_id) VALUES (:refreshToken, :userId)');
    $stmt->bindParam(':refreshToken', $refreshToken);
    $stmt->bindParam(':userId', $user['id'], PDO::PARAM_INT);
    $stmt->execute();

    // Issue authentication token and return
    $data['token'] = $session['token'];
    $data['expires'] = $session['expires'];
    $data['refreshToken'] = $refreshToken;

    return APIResponse::withSuccess($response, $data);

  } catch (UserNotExistsException $e) {
    return APIResponse::witherror($response, $e, HttpCodes::NOT_FOUND);
  } catch (BadFunctionCallException $e) {
    return APIResponse::witherror($response, $e, HttpCodes::BAD_REQUEST);
  } catch (Exception $e) {
    return APIResponse::witherror($response, $e, HttpCodes::UNATHORIZED);
  }
});

