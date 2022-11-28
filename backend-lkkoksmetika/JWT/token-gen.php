<?php

require_once 'jwt-lib.php';
require_once '../config.php';

$headers = Config::$jwtHeader;
$payload = array('sub'=>'1234567890','user'=>'John Doe', 'exp'=>(time() + Config::$authExpirationSeconds));

$jwt = generate_jwt($headers, $payload, Config::$authSecret);

echo $jwt;