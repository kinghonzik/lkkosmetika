<?php

require_once 'DB.php';
require_once 'config.php';

function DeleteProduct() 
{

  try {
    $dbConn = DB::Get();
    $product = json_decode(file_get_contents("php://input"));
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
  catch(Exception $e) {
    $dbConn->rollback();
    http_response_code(403);
    echo json_encode($e);
  }
}
?>