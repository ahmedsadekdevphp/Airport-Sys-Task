<?php
require 'vendor/autoload.php';

use \Firebase\JWT\JWT;

class JWTService {
    private static $key = "gldgjfdlgdflgdflfsffrfw";
    private static $algo = "HS256";

    public static function generateToken($user_data) {
        $payload = [
            "iss" => "airport",
            "iat" => time(),
            "exp" => time() + (60 * 60),
            "data" => $user_data
        ];
        return JWT::encode($payload, self::$key, self::$algo);
    }

    public static function validateToken($jwt) {
        try {
            $decoded = JWT::decode($jwt, self::$key, [self::$algo]);
            return (array) $decoded->data;
        } catch (Exception $e) {
            return false;
        }
    }
}
