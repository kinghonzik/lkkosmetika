<?php

require_once 'DB.php';
/*
function UpdateBase($dbConn, $data, $tableName) 
{
  $sql = "UPDATE `" . $tableName . "` set data = ? WHERE id = ?";
  $stmt= $dbConn->prepare($sql);
  $result = $stmt->execute(array(json_encode($data->data), (int)$data->id));   
}

function UpdateManufacturer() 
{
  try {
    $dbConn = DB::Get();
    $dbConn->beginTransaction();
    $data = json_decode(file_get_contents("php://input"));
    $result = UpdateBase($dbConn, $data, 'manufacturer');
    $dbConn->commit();
    http_response_code(200);
    echo json_encode(true);

  } catch(Exception $e) {
    $dbConn->rollback();
    http_response_code(403);
    echo json_encode($e);
  }
}

function UpdateCategory() 
{
  try {
    $dbConn = DB::Get();
    $dbConn->beginTransaction();
    $data = json_decode(file_get_contents("php://input"));
    $result = UpdateBase($dbConn, $data, 'category');
    $dbConn->commit();
    http_response_code(200);
    echo json_encode(true);

  } catch(Exception $e) {
    $dbConn->rollback();
    http_response_code(403);
    echo json_encode($e);
  }
}

function UpdateUsage() 
{
  try {
    $dbConn = DB::Get();
    $dbConn->beginTransaction();
    $data = json_decode(file_get_contents("php://input"));
    $result = UpdateBase($dbConn, $data, 'usage');
    $dbConn->commit();
    http_response_code(200);
    echo json_encode(true);

  } catch(Exception $e) {
    $dbConn->rollback();
    http_response_code(403);
    echo json_encode($e);
  }
}
*/
function updateImage($image) 
{
  $image_parts = explode(";base64,", $image->dataBase64String);
  $imgDecoded = base64_decode($image_parts[1]);
  file_put_contents(Config::IMG_FOLDER . $image->name, $imgDecoded);
  $image->src = $image->name;
  $image->savedOnServer = true;
  unset($image->dataBase64String);
  unset($image->name);
  $image->status = null;
}

function UpdateProduct() 
{
  try {
    $dbConn = DB::Get();
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
    $product->data->additionalImages = array_filter($product->data->additionalImages, function($v, $k) { return $v->status != 'delete'; }, ARRAY_FILTER_USE_BOth);
    $sql = "UPDATE product set data = ? WHERE id = ?";
    $stmt= $dbConn->prepare($sql);
    $result = $stmt->execute(array(json_encode($product->data), (int)$product->id));   
    http_response_code(200);
    echo json_encode(true);

  } catch(Exception $e) {
    $dbConn->rollback();
    http_response_code(403);
    echo json_encode($e);
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

    $id = (int)$order->id;
    $data = $order->data;
    $state = $order->state;
    $mailToCustomer = $order->mailToCustomer;
    $mailToCustomerDesc = $order->mailToCustomerDesc;
    $mailToCustomerInvoice = $order->mailToCustomerInvoice;
    $mailHtml = $order->mailHtml;
    $sentEmails = $order->sentEmails;

    $response = new stdClass;

    if ($mailToCustomer) {
      // TODO: poslani mailu
      $to = $data->contact->email;
      $subject = "LK kosmetika - Změna stavu objednávky";
      
      $message = "<html>
                  <head>
                  <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
                  <style> 
                    .title { padding: 5px; } 
                    .data-row { padding: 5px; } 
                    .mail-content td, .mail-content th { padding: 5px; }
                    .mail-contact { padding-top: 10px; } 
                    .footer { padding: 5px;}</style>
                  </head>
                  <body>";
      $message .= "\r\n";
      $message .= $mailHtml;
      $message .="</body></html>";
      
      $header = 'MIME-Version: 1.0' . "\r\n";
      $header .= 'Content-Type: text/html; charset=UTF-8' . "\r\n";
      $header .= "From:info@lkkosmetika.cz \r\n";

      $retval = mail($to,$subject,$message,$header);
      
      if($retval == true ) {
          $response->mailSent = true;
          $mailInfo = new stdClass;
          $mailInfo->email = $data->contact->email;
          $mailInfo->state = $state;
          $mailInfo->datetime = date("c");
          $mailInfo->desc = $mailToCustomerDesc;
          $mailInfo->invoice = $mailToCustomerInvoice;
          if (!$sentEmails || empty($sentEmails))
            $sentEmails = [];
          array_push($sentEmails, $mailInfo);
      }else {
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

  } catch(Exception $e) {
    http_response_code(403);
    echo json_encode($e);
  }
}

function UpdateOrder() 
{
  try {
    $dbConn = DB::Get();
    $input = json_decode(file_get_contents("php://input"));
    $order = $input->order;

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

  } catch(Exception $e) {
    $dbConn->rollback();
    http_response_code(403);
    echo json_encode($e);
  }
}

function UpdateConfig() 
{
  try {
    $dbConn = DB::Get();
    $dbConn->beginTransaction();
    $data = file_get_contents("php://input");
    $decodedData = json_decode($data);
    $dbConn->prepare("DELETE FROM `config`")->execute();
    $dbConn->prepare("INSERT INTO `config` VALUES(?)")->execute(array($data));

    $dbConn->commit();
    http_response_code(200);
    echo json_encode(true);

  } catch(Exception $e) {
    $dbConn->rollback();
    http_response_code(403);
    echo json_encode($e);
  }
}

?>