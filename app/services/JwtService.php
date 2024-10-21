<?php
namespace App\Services;
require '../vendor/autoload.php';
use \Firebase\JWT\JWT;
use Exception;
class JwtService
{

    public static function generateToken($user_data)
    {
        $payload = [
            "iss" => "airport",
            "iat" => time(),
            "exp" => time() + (60 * 60),
            "data" => $user_data
        ];
        return JWT::encode($payload, config('APP_SECRET_KEY'), 'HS256');
    }

    public static function validateToken($jwt)
    {
        try {
            $decoded = \Firebase\JWT\JWT::decode($jwt, new \Firebase\JWT\Key(config('APP_SECRET_KEY'), 'HS256'));
            return (array) $decoded->data;
        } catch (Exception $e) {
            return false;
        }
    }
}
