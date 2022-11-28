<?php

require_once 'jwt-lib.php';
require_once '../config.php';

$credentials = json_decode(file_get_contents("php://input"));

if (isSet($credentials) && isSet($credentials->user) && isSet($credentials->pass)) {

    $user = $credentials->user;
    $pass = $credentials->pass;

    if ($user == 'lkkosmetika' && $pass == 'lkkosmetika') {
        $payload = array('user'=>$user, 'exp'=>(time() + Config::$authExpirationSeconds), 'expLength'=> Config::$authExpirationSeconds);
        $jwt = generate_jwt(Config::$jwtHeader, $payload, Config::$authSecret);

        http_response_code(200);
        echo json_encode(array('success' => true, 'token' => $jwt));
        return;
    }
} 

http_response_code(200);
echo json_encode(array('success' => false));
    





