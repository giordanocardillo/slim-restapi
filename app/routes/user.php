<?php

use Firebase\JWT\ExpiredException;
use RestAPI\Exceptions\InvalidTokenException;
use RestAPI\Exceptions\UserAlreadyExistsException;
use RestAPI\Exceptions\UserNotExistsException;
use RestAPI\Utils\APIResponse;
use RestAPI\Utils\HttpCodes;
use RestAPI\Utils\Password;
use RestAPI\Utils\SessionManager;
use Slim\Http\Request as SlimRequest;
use Slim\Http\Response as SlimResponse;

$app->post('/user', function (SlimRequest $request, SlimResponse $response) {
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

    // Check if username exists in database
    $stmt = $db->prepare('SELECT id FROM users WHERE username = :username');
    $stmt->bindParam(':username', $queryParams['username']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (count($user) > 0) {
      throw new UserAlreadyExistsException();
    }

    // Insert user in database
    $stmt = $db->prepare('INSERT INTO users (username, password) VALUES (:username, :password)');
    $stmt->bindParam(':username', $queryParams['username']);
    $stmt->bindParam(':password', Password::hash($queryParams['password']));
    $stmt->execute();

    $data['userId'] = $db->lastInsertId();

    return APIResponse::withSuccess($response, $data);
  } catch (PDOException $e) {
    return APIResponse::witherror($response, $e, HttpCodes::INTERNAL_SERVER_ERROR);
  } catch (UserAlreadyExistsException $e) {
    return APIResponse::witherror($response, $e, HttpCodes::BAD_REQUEST);
  } catch (BadFunctionCallException $e) {
    return APIResponse::witherror($response, $e, HttpCodes::BAD_REQUEST);
  } catch (Exception $e) {
    return APIResponse::witherror($response, $e, HttpCodes::INTERNAL_SERVER_ERROR);
  }

});
