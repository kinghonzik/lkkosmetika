<?php

require_once 'CRSF.php';
require_once 'model-insert.php';

try {
    $tokenObj = getCRSF();
    http_response_code(200);
    echo $tokenObj->token;
}
catch (Exception $e) {
    InsertError($e, 'WEBAPI-' . __FUNCTION__);
    http_response_code(403);
    echo 'false';
}