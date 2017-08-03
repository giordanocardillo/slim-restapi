<?php

namespace RestAPI\Utils;

use Firebase\JWT\JWT;
use Slim\Http\Request as SlimRequest;
use RestAPI\Exceptions\UnauthorizedException;

class SessionManager {

  public static function issueRefreshToken() {
    $refreshToken = bin2hex(openssl_random_pseudo_bytes(48));
    return hash("sha256", $refreshToken);
  }

  public static function checkSession(SlimRequest $request) {

    $configuration = ConfigurationManager::getInstance()->getSession();

    if (!$request->hasHeader('Authorization')) {
      throw new UnauthorizedException('No token provided');
    }

    $token = preg_replace('/^Bearer\\s/', '', $request->getHeader('Authorization')[0]);
    return JWT::decode($token, $configuration->JWTKeys, array('HS256'));
  }

  public static function issueToken($userID) {
    $configuration = ConfigurationManager::getInstance()->getSession();
    $kid = array_rand($configuration->JWTKeys);
    return JWT::encode(
      [
        'id' => $userID,
        'exp' => strtotime("+$configuration->expireMinutes minute"),
        'iss' => $_SERVER['HTTP_HOST'],
        'nbf' => time()
      ],
      $configuration->JWTKeys[$kid],
      'HS256',
      $kid);
  }
}
