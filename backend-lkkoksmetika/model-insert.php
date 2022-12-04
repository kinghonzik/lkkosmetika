<?php

require_once 'DB.php';
require_once 'config.php';
require_once 'model-get.php';
require_once 'file-functions.php';

  function InsertProduct() 
  {
    try {
      $dbConn = DB::Get();
      $dbConn->beginTransaction();
      $product = json_decode(file_get_contents("php://input"));
      if ($product->data->image->status == 'update') {
        updateImage($product->data->image);
      }
      foreach ($product->data->additionalImages as $item) {
        if ($item->status == 'update')
          updateImage($item);
        if ($item->status == 'delete' && $item->src)
          unlink(Config::IMG_FOLDER . $item->src);
      }
      $product->data->additionalImages = array_filter($product->data->additionalImages, function($v, $k) { return $v->status != 'delete'; }, ARRAY_FILTER_USE_BOTH);
      $sql = "INSERT INTO product (`data`) VALUES (?)";
      $stmt= $dbConn->prepare($sql);
      $result = $stmt->execute(array(json_encode($product->data)));   
      $dbConn->commit();
      http_response_code(200);
      echo json_encode(true);

    } catch(Exception $e) {
      $dbConn->rollback();
      http_response_code(403);
      echo json_encode($e);
    }
  }

  function InsertOrder() 
  {
    try {
      $dbConn = DB::Get();
      //$dbConn->beginTransaction();
      $input = json_decode(file_get_contents("php://input"));
      $order = $input->order;
      $data = $order->data;
      $shipping = $order->shipping;
      $payment = $order->payment;
      $productsPrice = (double)$order->productsPrice;
      $totalPrice = (double)$order->totalPrice;
      $paymentPrice = (double)$order->paymentPrice;
      $shippingPrice = (double)$order->shippingPrice;
      $state = 'nová';
      $created = date("c");

      // validace tady
      $sql = "INSERT INTO `order` (`created`,`data`,`state`,`shipping`,`payment`,`paymentPrice`,`shippingPrice`,`productsPrice`,`totalPrice`,`sentEmails`) VALUES (?,?,?,?,?,?,?,?)";
      $stmt= $dbConn->prepare($sql);
      $result = $stmt->execute(array($created, json_encode($data), $state, $shipping, $payment, $paymentPrice, $shippingPrice, $productsPrice, $totalPrice, null));   
      //$dbConn->commit();
      http_response_code(200);
      echo json_encode(true);
    } catch(Exception $e) {
      //$dbConn->rollback();
      http_response_code(403);
      echo json_encode($e);
    }
  }

?>