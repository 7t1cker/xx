<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
function generateJWTToken($user_id) {
    global $secretKey; 

    $issued_at = time();
    $expiration_time = $issued_at + (60 * 60); 

    $payload = array(
        'iat' => $issued_at,
        'exp' => $expiration_time,
        'sub' => $user_id
    );

    $header = array(
        "alg" => "HS256",
        "typ" => "JWT",
        "kid" => "kid" 
    );

    return JWT::encode($payload, $secretKey, 'HS256', null, $header);
}

function isValidJWTToken($token, $secretKey) {
    try {
        $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
        return true;
    } catch (Exception $e) {
        return false;
    }
}


?>