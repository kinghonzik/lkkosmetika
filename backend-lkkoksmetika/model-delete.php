<?php

require_once 'global.php';
require_once 'DB.php';
require_once 'config.php';
require_once 'JWT/jwt-lib.php';
require_once 'model-get.php';

function DeleteProduct() 
{

  try {
    $dbConn = DB::Get();
    $input = json_decode(file_get_contents("php://input"));
    $product = $input->product;
    $token = $input->token;
    if (!is_jwt_valid($token, Config::$authSecret)) {
      throw new AuthException();
    }
    if (isset($product->data->image->src)) {
      unlink(Config::IMG_FOLDER . $product->data->image->src);
    }
    foreach ($product->data->additionalImages as $item) {
      unlink(Config::IMG_FOLDER . $item->src);
    }
    $dbConn->prepare("DELETE FROM `product` WHERE id = ". (int)$product->id)->execute();
    http_response_code(200);
    echo json_encode(true);
  } 
  catch(AuthException $ae) {
    http_response_code(401);
    echo json_encode('Unauthorized');
  }
  catch(Exception $e) {
    InsertError($e, 'WEBAPI-' . __FUNCTION__);
    http_response_code(403);
    echo json_encode(false);
  }
}
?>