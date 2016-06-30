<?php
use Slim\Http\Request as SlimRequest;

class SessionManager {

    const SESSION_EXPIRE_MINUTES = 180;

    //FIXME Add encryption keys
    protected static function getSecretKeys() {
        return array(
            '',
            '',
            '',
            '',
            ''
        );
    }

    public static function checkSession(SlimRequest $request) {

        if (!$request->hasHeader('Authorization')) {
            throw new UnauthorizedException('No token provided');
        }

        $token = preg_replace('/^Bearer\\s/', '', $request->getHeader('Authorization')[0]);
        return JWT::decode($token, self::getSecretKeys(), array('HS256'));
    }

    public static function issueToken($userID) {
        $keys = self::getSecretKeys();
        $kid = array_rand($keys);
        return JWT::encode(array('id' => $userID, 'exp' => time() + (self::SESSION_EXPIRE_MINUTES * 60), 'nbf' => time()), $keys[$kid], 'HS256', $kid);
    }
}
