<?php

require_once 'config.php';

function getCRSF() {
    if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
        session_start();
    };

    if (!isset($_SESSION['tokens'])) {
        $_SESSION['tokens'] = array();
    }

    $newToken = md5(uniqid(mt_rand(), true));
    $newItem = new stdClass;
    $newItem->token = $newToken;
    $newItem->datetime = date('c');
    array_push($_SESSION['tokens'], $newItem);

    return $newItem;
}

function checkCRSF($token) {
    if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
        session_start();
    };

    if (!isset($_SESSION['tokens'])) {
        return 'not_found';
    }

    $fItem = null;
    foreach ($_SESSION['tokens'] as $item) {
        if($item->token == $token) {
            $fItem = $item;
            break;
        }
    }

    if ($fItem) {
        $date1 = new DateTime($fItem->datetime);
        $date2 = new DateTime(date('c'));
        $diff = $date2->diff($date1);
        $sec = $date2->getTimestamp() - $date1->getTimestamp();
        if ($sec > Config::$CRSFExpirationSec) {
            return 'expired';
        }
        return 'ok';
    }

    return 'not_found';
}

function deleteToken($token) {
    if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
        session_start();
    };

    if (!isset($_SESSION['tokens'])) {
        return false;
    }

    foreach($_SESSION['tokens'] as $key => $val) {
        if($val->token == $token) {
           unset($_SESSION['tokens'][$key]);
           break;
        }
     }
    return true;
}

/*
$token = getCRSF();
echo $token->datetime . " - " . $token->token;
*/
/*
echo checkCRSF('b5c08ecd240730247c4871731e85388d');
echo "<br>";
echo deleteToken('b5c08ecd240730247c4871731e85388d');
echo "<br>";
echo checkCRSF('b5c08ecd240730247c4871731e85388d');
*/
/*
echo "<br> all tokens <br>";
foreach ($_SESSION['tokens'] as $item) {
    echo $item->token . " " . $item->datetime . "<br>";
}*/