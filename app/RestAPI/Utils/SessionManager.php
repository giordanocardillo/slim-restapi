<?php

namespace RestAPI\Utils;

use BadFunctionCallException;
use Firebase\JWT\JWT;
use Slim\Http\Request as SlimRequest;
use RestAPI\Exceptions\UnauthorizedException;

class SessionManager {

  public static function issueRefreshToken() {
    $refreshToken = bin2hex(openssl_random_pseudo_bytes(48));
    return hash("sha256", $refreshToken);
  }

  public static function checkSessionToken(SlimRequest $request) {

    $authorizationHeader = self::getAuthorizationHeader($request);

    $configuration = ConfigurationManager::getInstance()->getSession();

    $sessionToken = preg_replace('/^Bearer\\s/', '', $authorizationHeader);

    return JWT::decode($sessionToken, $configuration->JWTKeys, array('HS256'));
  }

  private static function getAuthorizationHeader(SlimRequest $request) {

    if (!$request->hasHeader('Authorization')) {
      throw new BadFunctionCallException('Must provide Authorization header');
    }

    return $request->getHeader('Authorization')[0];
  }

  public static function getSessionPayload(SlimRequest $request) {
    $authorizationHeader = self::getAuthorizationHeader($request);
    $sessionToken = preg_replace('/^Bearer\\s/', '', $authorizationHeader);
    $sessionPayload64 = explode('.', $sessionToken)[1];
    $sessionPayload = JWT::jsonDecode(JWT::urlsafeB64Decode($sessionPayload64));
    return $sessionPayload;
  }

  public static function issueSession($userID) {
    $session = [];
    $configuration = ConfigurationManager::getInstance()->getSession();

    $expiration = strtotime("+$configuration->expireMinutes minute");

    $kid = array_rand($configuration->JWTKeys);
    $session['token'] = JWT::encode(
      [
        'id' => $userID,
        'exp' => $expiration,
        'iss' => $_SERVER['HTTP_HOST'],
        'nbf' => time()
      ],
      $configuration->JWTKeys[$kid],
      'HS256',
      $kid);

    $session['expires'] = $expiration;

    return $session;

  }
}
