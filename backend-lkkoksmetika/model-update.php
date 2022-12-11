<?php

require_once 'global.php';
require_once 'DB.php';
require_once 'file-functions.php';
require_once 'model-get.php';
require_once 'model-insert.php';
require_once 'mail.php';
require_once 'JWT/jwt-lib.php';

function UpdateProduct() 
{
  try {
    $dbConn = DB::Get();
    $input = json_decode(file_get_contents("php://input"));
    $product = $input->product;
    $token = $input->token;
    if (!is_jwt_valid($token, Config::$authSecret)) {
      throw new AuthException();
    }
    if ($product->data->image->status == 'update') {
      updateImage($product->data->image);
    }
    if (!isSet($product->data->additionalImages))
      $product->data->additionalImages = array();

    foreach ($product->data->additionalImages as $item) {
      if (isSet($item->status) && $item->status == 'update')
        updateImage($item);
      if (isSet($item->status) && $item->status == 'delete' && $item->src)
        unlink(Config::IMG_FOLDER . $item->src);
    }
    $product->data->additionalImages = array_filter($product->data->additionalImages, function($v, $k) { return $v->status != 'delete'; }, ARRAY_FILTER_USE_BOTH);
    $sql = "UPDATE product set data = ? WHERE id = ?";
    $stmt= $dbConn->prepare($sql);
    $result = $stmt->execute(array(json_encode($product->data), (int)$product->id));   
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

function getOrderRecap($order) {
  $str = "";
  $str .= "<div class='data-row'>Rekapitulace: </div>";
  $str .= "<table>";
  $str .= "<thead>";
  $str .= "<tr>";
  $str .= "<th class='t-cell'>#</th>";
  $str .= "<th class='t-cell'>Název</th>";
  $str .= "<th class='t-cell'>Počet</th>";
  $str .= "<th class='t-cell'>Cena (mj)</th>";
  $str .= "<th class='t-cell'>Cena</th>";
  $str .= "</tr>";
  $str .= "</thead>";
  $str .= "<tbody>";
  foreach ($order->data->products as $index => $item) {
    $str .= "<tr>";
    $str .= "<td class='t-cell'>" . ((int)$index + 1) . "</td>";
    $str .= "<td class='t-cell'>" . $item->title . (isSet($item->variant) ? ' - ' . $item->variant : '') . "</td>";
    $str .= "<td class='t-cell'>" . $item->count . "</td>";
    $str .= "<td class='t-cell'>" . $item->price . "</td>";
    $str .= "<td class='t-cell'>" . $item->price * $item->count . "</td>";
    $str .= "</tr>";
  }
  $str .= "</tbody>";
  $str .= "</table>";
  $str .= "<div class='data-row'>Doprava: " . $order->shipping . " - " . $order->shippingPrice . "</div>";
  $str .= "<div class='data-row'>Platba cena: " . $order->payment . " - " . $order->paymentPrice . "</div>";
  $str .= "<div class='data-row'>Celková cena: <b>" . $order->totalPrice . "</b> </div>";
  return $str;
}


function UpdateOrderState() 
{
  try {
    $dbConn = DB::Get();
    $input = json_decode(file_get_contents("php://input"));
    $order = $input->order;
    $token = $input->token;
    if (!is_jwt_valid($token, Config::$authSecret)) {
      throw new AuthException();
    }

    $id = (int)$order->id;
    $data = $order->data;
    $state = $order->state;
    $mailToCustomer = $order->mailToCustomer;
    $mailToCustomerDesc = $order->mailToCustomerDesc;
    $mailToCustomerInvoice = $order->mailToCustomerInvoice;
    $mailHtml = $order->mailHtml;
    $sentEmails = $order->sentEmails;
    $config = GetConfig();

    $response = new stdClass;

    if ($mailToCustomer) {
      $to = $data->contact->email;
      $from = $config->mailFrom;
      $subject = "Změna stavu objednávky";
      $retval = sendMail($to,$from,$subject,$mailHtml);

      if ($retval == true ) {
          $response->mailSent = true;
          $mailInfo = new stdClass;
          $mailInfo->email = $data->contact->email;
          $mailInfo->state = $state;
          $mailInfo->success = $retval;
          $mailInfo->datetime = date("c");
          $mailInfo->desc = $mailToCustomerDesc;
          $mailInfo->invoice = $mailToCustomerInvoice;
          if (!$sentEmails || empty($sentEmails))
            $sentEmails = [];
          array_push($sentEmails, $mailInfo);
      } else {
         $response->mailSent = false;
         $response->mailSentError = 'Z nějakého důvodu se nezdařilo odeslat mail.';
      }
    }

    if ($mailToCustomer &&  $response->mailSent == false) {
      http_response_code(403);  
      $response->status = 'NOK';
      echo json_encode($response);
      return;
    }

    $sql = "UPDATE `order` set data = ?, sentEmails = ?, state = ? WHERE id = ?";
    $stmt= $dbConn->prepare($sql);
    $result = $stmt->execute(array(json_encode($data), json_encode($sentEmails), $state, $id));   
    http_response_code(200);
    $response->status = 'OK';
    echo json_encode($response);

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

function UpdateOrder() 
{
  try {
    $dbConn = DB::Get();
    $input = json_decode(file_get_contents("php://input"));
    $order = $input->order;
    $token = $input->token;
    if (!is_jwt_valid($token, Config::$authSecret)) {
      throw new AuthException();
    }

    $id = (int)$order->id;
    $data = $order->data;
    $shipping = $order->shipping;
    $payment = $order->payment;
    $productsPrice = (double)$order->productsPrice;
    $totalPrice = (double)$order->totalPrice;
    $paymentPrice = (double)$order->paymentPrice;
    $shippingPrice = (double)$order->shippingPrice;

    $sql = "UPDATE `order` set data = ?, shipping = ?, payment = ?, paymentPrice = ?, shippingPrice = ?, productsPrice = ?, totalPrice = ?  WHERE id = ?";
    $stmt= $dbConn->prepare($sql);
    $result = $stmt->execute(array(json_encode($data), $shipping, $payment, $paymentPrice, $shippingPrice, $productsPrice, $totalPrice, $id));   
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

function UpdateConfig() 
{
  try {
    $dbConn = DB::Get();
    $input = json_decode(file_get_contents("php://input"));
    $data = $input->config;
    $token = $input->token;
    if (!is_jwt_valid($token, Config::$authSecret)) {
      throw new AuthException();
    }
    $dbConn->prepare("DELETE FROM `config`")->execute();
    $dbConn->prepare("INSERT INTO `config` VALUES(?)")->execute(array(json_encode($data)));
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


function UpdateDocs() 
{
  try {
    $dbConn = DB::Get();
    $input = json_decode(file_get_contents("php://input"));
    $token = $input->token;
    if (!is_jwt_valid($token, Config::$authSecret)) {
      throw new AuthException();
    }
    $dbConn->prepare("DELETE FROM `docs`")->execute();
    $dbConn->prepare("INSERT INTO `docs` VALUES(?,?)")->execute(array($input->conditions, $input->GDPR));
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