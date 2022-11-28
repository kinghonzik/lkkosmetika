<?php

require_once 'jwt-lib.php';
require_once '../config.php';

$token = json_decode(file_get_contents("php://input"));
$is_jwt_valid = is_jwt_valid($token, Config::$authSecret);
$payload = get_jwt_payload($token);

if ($is_jwt_valid) {
    $user = $payload->user;
    $payload = array('user'=>$user, 'exp'=>(time() + Config::$authExpirationSeconds), 'expLength'=> Config::$authExpirationSeconds);
    $jwt = generate_jwt(Config::$jwtHeader, $payload, Config::$authSecret);

    http_response_code(200);
    echo json_encode(array('success' => true, 'token' => $jwt));
    return;
} 

http_response_code(200);
echo json_encode(array('success' => false));
    





