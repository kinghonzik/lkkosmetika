<?php

require_once 'jwt-lib.php';
require_once '../config.php';

$token = $_GET['token'];

$is_jwt_valid = is_jwt_valid($token, Config::$authSecret);

echo nl2br("\n");

if($is_jwt_valid === TRUE) {
	echo 'JWT is valid';
} else {
	echo 'JWT is invalid';
}

echo nl2br("\n");
echo nl2br("\n");

echo "Payload:";
echo nl2br("\n");
echo get_jwt_payload($token);


echo nl2br("\n");
