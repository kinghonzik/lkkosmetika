<?php

require_once 'global.php';
require_once 'DB.php';
require_once 'config.php';
require_once 'model-get.php';
require_once 'file-functions.php';
require_once 'mail.php';
require_once 'JWT/jwt-lib.php';

  function InsertProduct() 
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
        if ($item->status == 'update')
          updateImage($item);
        if ($item->status == 'delete' && $item->src)
          unlink(Config::IMG_FOLDER . $item->src);
      }
      $product->data->additionalImages = array_filter($product->data->additionalImages, function($v, $k) { return $v->status != 'delete'; }, ARRAY_FILTER_USE_BOTH);
      $sql = "INSERT INTO product (`data`) VALUES (?)";
      $stmt= $dbConn->prepare($sql);
      $result = $stmt->execute(array(json_encode($product->data))); 

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
      echo json_encode($e);
    }
  }

  function InsertError($message, $type) 
  {
    try {
      $dbConn = DB::Get();
      $sql = "INSERT INTO `errors` (`type`,`msg`) VALUES (?, ?)";
      $stmt = $dbConn->prepare($sql);
      $result = $stmt->execute(array($message, $type));   
      return true;

    } catch(Exception $e) {
      return false;
    }
  }

  function InStr($string, $maxLength = 128) {
    if($string && strlen($string) > $maxLength)
      return substr($string, 0, $maxLength - 1);
    
    return $string;
  }

  class ValidationException extends Exception {}

  function InsertOrder() 
  {
    try {
      $dbConn = DB::Get();
      //$dbConn->beginTransaction();
      $input = json_decode(file_get_contents("php://input"));
      $config = GetConfig();
      $order = $input;
      $data = $order->data;

      // validace formulare
      $data = new stdClass;
      $data->contact = new stdClass;
      $data->contact->firstname = InStr($order->data->contact->firstname);
      $data->contact->lastname = InStr($order->data->contact->lastname);
      $data->contact->city = InStr($order->data->contact->city);
      $data->contact->zip = InStr($order->data->contact->zip);
      $data->contact->street = InStr($order->data->contact->street);
      $data->contact->houseNumber = InStr($order->data->contact->houseNumber);
      $data->contact->email = InStr($order->data->contact->email);
      $data->contact->phone = InStr($order->data->contact->phone);
      $data->contact->company = InStr($order->data->contact->company);

      $data->billingContact = new stdClass;
      $data->billingContact->firstname = InStr($order->data->billingContact->firstname);
      $data->billingContact->lastname = InStr($order->data->billingContact->lastname);
      $data->billingContact->city = InStR($order->data->billingContact->city);
      $data->billingContact->zip = InStr($order->data->billingContact->zip);
      $data->billingContact->street = InStr($order->data->billingContact->street);
      $data->billingContact->houseNumber = InStr($order->data->billingContact->houseNumber);
      $data->billingContact->company = InStr($order->data->billingContact->company);

      $data->bllingAddressSame = (bool)$order->data->bllingAddressSame;
      $data->consentGDPR = (bool)$order->data->consentGDPR;
      $data->consentBusinessConditions = (bool)$order->data->consentBusinessConditions;
      $data->customerDescription = InStr($order->data->customerDescription, 1000);

      $data->products = array();
      $dbProducts = GetBase($dbConn, 'product');
      if(count($order->data->products) == 0) {
        throw new ValidationException('V objednavce nejsou zadne produkty.');
      }
      if (count($order->data->products) > (count($dbProducts) * 2)) {
        throw new ValidationException('V objednavce je prilis mnoho produktu.');
      }
      if (empty($data->contact->firstname) || empty($data->contact->lastname) || empty($data->contact->city)
        || empty($data->contact->zip) || empty($data->contact->street) || empty($data->contact->houseNumber)
        || empty($data->contact->email) || empty($data->contact->phone)) {
        throw new ValidationException('V objednávce u kontaktni adresy chybi nektery z povinnych udaju');
      }

      // sparovani produktu s databazi
      foreach($order->data->products as $oProduct) 
      {
        $dbProduct = null;
        foreach($dbProducts as $dbP) {
          if ((int)$dbP->id == (int)$oProduct->id) {
            $dbProduct = $dbP;
            break;
          }
        }

        if ($dbProduct == null) {
          throw new ValidationException('Produkt s ID ' . $oProduct->id . ' se nepodarilo v databazi dohledat!');
        }

        $p = new stdClass;
        $p->id=(int)$dbProduct->id;
        $p->title=$dbProduct->data->title;
        $p->variant=InStr($oProduct->variant, 256);
        $p->count=(int)($oProduct->count);
        $p->price=(double)$oProduct->price;

        if ($p->variant) {
          $variantFound = false;
          foreach($dbProduct->data->variants as $variant) {
            if ($variant->title == $p->variant) {
              $p->price = (double)$variant->price;
              $variantFound = true;
              break;
            }
          }
          if (!$variantFound) {
            throw new ValidationException('Variantu' . $p->variant . ' od produktu ' . $oProduct->id . ' se nepodarilo v databazi dohledat!');
          }
        }
        else 
          $p->price=(double)$dbProduct->data->price;

        array_push($data->products, $p);
      }

      // validace shipping
      $shippingObj = null;
      foreach ($config->shippingOptions as $item) {
        if ($item->id == $order->shipping) {
          $shippingObj = $item;
          break;
        }
      }
      if (!$shippingObj) 
        throw new ValidationException('Doprava ' . $order->shipping . ' v systemu nenalezena');
      
      $paymentObj = null;
      foreach ($config->paymentOptions as $item) {
        if ($item->id == $order->payment) {
          $paymentObj = $item;
          break;
        }
      }
      if (!$paymentObj) 
        throw new ValidationException('Platba ' . $order->payment . ' v systemu nenalezena');
      
      $shipping = $shippingObj->id;
      $payment = $paymentObj->id;
      $paymentPrice = (double)$paymentObj->price;
      $shippingPrice = (double)$shippingObj->price;

      $productsPrice = 0;
      foreach ($data->products as $product) {
        $productsPrice += ((double)$product->price * (int)$product->count);
      }
      $totalPrice = $productsPrice + $paymentPrice + $shippingPrice;

      // validace celkove ceny
      if ((double)$productsPrice != (double)$order->productsPrice) {
        throw new ValidationException('Cena produktu nesedi ' . $productsPrice . ' ' . $order->productsPrice);
      }

      if ((double)$totalPrice != (double)$order->totalPrice) {
        throw new ValidationException('Celkova cena nesedi ' . $totalPrice . ' ' . $order->totalPrice);
      }

      $state = 'nová';
      $created = date("c");
      $sql = "INSERT INTO `order` (`created`,`data`,`state`,`shipping`,`payment`,`paymentPrice`,`shippingPrice`,`productsPrice`,`totalPrice`,`sentEmails`) VALUES (?,?,?,?,?,?,?,?,?,?)";
      $stmt= $dbConn->prepare($sql);
      $result = $stmt->execute(array($created, json_encode($data), $state, $shipping, $payment, $paymentPrice, $shippingPrice, $productsPrice, $totalPrice, null));
      $orderId = (int)$dbConn->lastInsertId();

      $mailToAdmin = false;
      // info mail
      try {
        $to = str_replace(PHP_EOL, ',', $config->mails);
        $from = $config->mailFrom;
        $subject = "Nová objednávka na LKkosmetika Eshopu!";
        $mailHtml = "<div class='data-row'> Máš novou objednávku za celkem " . $totalPrice . " " . $config->priceUnit . "  </div>";
        $mailHtml .= "<div class='data-row'><a href='https://lkkosmetika.cz/admin/orders' target='_blank'>Zobrazit objednávky</a></div>";
        $retval = sendMail($to,$from,$subject,$mailHtml);
        if (!$retval) {
          throw new Exception('Mail se nezdarilo z nejakeho duvodu odeslat! (mail funkce vratila false)');
        }
        $mailToAdmin = true;
      } catch (Exception $e) {
        InsertError($e, 'WEBAPI-sendMail');
      }

      // mail zakaznikovi
      $mailToCustomer = false;
      try {
        $to = $order->data->contact->email;
        $from = $config->mailFrom;
        $subject = "Potvrzeni objednávky";
        $mailHtml = $order->mailHtml;
        $retval = sendMail($to,$from,$subject,$mailHtml);
        if (!$retval) {
          throw new Exception('Mail se nezdarilo z nejakeho duvodu odeslat! (mail funkce vratila false)');
        }
        $mailToCustomer = true;
      } catch (Exception $e) {
        InsertError($e, 'WEBAPI-sendMail-newOrder');
      }

      // vlozeni zaznamu s odeslanou rekapitulaci
      $sentEmails = array();
      $mailInfo = new stdClass;
      $mailInfo->email = $data->contact->email;
      $mailInfo->state = $state;
      $mailInfo->success = $retval;
      $mailInfo->datetime = date("c");
      $mailInfo->desc = 'Rekapitulace';
      $mailInfo->invoice = false;
      array_push($sentEmails, $mailInfo);
      $sql = "UPDATE `order` set sentEmails = ? WHERE id = ?";
      $stmt= $dbConn->prepare($sql);
      $result = $stmt->execute(array(json_encode($sentEmails),  $orderId));  

      // odpoved zpatky klientovi
      $response = new stdClass;
      $response->status = 'OK';
      $response->mailToAdmin = $mailToAdmin;
      $response->mailToCustomer = $mailToCustomer;
      
      http_response_code(200);
      echo json_encode($response);
    } catch(ValidationException $e) {
      InsertError($e->getMessage(), 'WEBAPI-FormValidation-' . __FUNCTION__);
      http_response_code(403);
      echo json_encode(false);
    }
    catch(Exception $e) {
      InsertError($e, 'WEBAPI-' . __FUNCTION__);
      http_response_code(403);
      echo json_encode(false);
    }
  }

?>