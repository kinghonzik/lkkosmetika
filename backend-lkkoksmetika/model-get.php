<?php

require_once 'DB.php';
require_once 'global.php';
require_once 'model-get.php';
require_once 'model-insert.php';
require_once 'JWT/jwt-lib.php';

function GetConfig()
{
  $dbConn = DB::Get();
  $query = $dbConn->prepare("SELECT * FROM config");
  $query->execute();

  $result = $query->fetch(PDO::FETCH_OBJ);
  return json_decode($result->data);
}

function GetDocs()
{
  $dbConn = DB::Get();
  $query = $dbConn->prepare("SELECT * FROM docs");
  $query->execute();

  $result = $query->fetch(PDO::FETCH_OBJ);
  return $result;
}

function GetBase($dbConn, $tableName) {
  $query = $dbConn->prepare("SELECT * FROM `" . $tableName . "`");
  $success = $query->execute();

  $records = $query->fetchAll(PDO::FETCH_OBJ);

  foreach ($records as $index => $item) {
    $item->data = json_decode($item->data);
  }

  return $records;
}

function GetErrors()
{
  try {
    $dbConn = DB::Get();
    $query = $dbConn->prepare("SELECT * FROM `errors` ORDER BY datetime DESC LIMIT 1000");
    $success = $query->execute();
    $records = $query->fetchAll(PDO::FETCH_OBJ);

    http_response_code(200);
    echo json_encode($records);
  } catch(Exception $e) {
    InsertError($e, 'WEBAPI-' . __FUNCTION__);
    http_response_code(403);
    echo json_encode(false);
  }
}

function GetProducts()
{
  try {
    $dbConn = DB::Get();
    $products = GetBase($dbConn, 'product');
    http_response_code(200);
    echo json_encode($products);
  } catch(Exception $e) {
    InsertError($e, 'WEBAPI-' . __FUNCTION__);
    http_response_code(403);
    echo json_encode(false);
  }
}


function GetProductByID()
{
  try {
    $id = $_GET['id'];
    $dbConn = DB::Get();
    $query = $dbConn->prepare("SELECT * FROM product where JSON_EXTRACT(data, '$.id') = ?");
    $query->execute(array($id));
    $product = $query->fetch(PDO::FETCH_OBJ);
    $product->data = json_decode($product->data);
    http_response_code(200);
    echo json_encode($product);
  } catch(Exception $e) {
    InsertError($e, 'WEBAPI-' . __FUNCTION__);
    http_response_code(403);
    echo json_encode(false);
  }
}

function GetOrders()
{
  try {
    $dbConn = DB::Get();

    $options = json_decode($_GET['options']);
    $limit = (int)$options->limit;
    if ($limit > 100)
      $limit = 100;
    $offset = (int)$options->offset;
    $filterBy = isSet($options->filterBy) ? $options->filterBy : null;
    $filterState = isSet($options->filterState) ? $options->filterState : null;
    $orderBy = isSet($options->orderBy) ? $options->orderBy : 'ID desc';

    $queryStr = "SELECT * FROM `order`";

    // filter by
    /*if ($filterBy) {
      $queryStr .= " WHERE " . implode(' AND ', $filterBy);
    }*/

    if ($filterState) {
      $queryStr .= " WHERE state = '" . $filterState . "'";
    }

    // order by
    if($orderBy) {
      $queryStr .= " ORDER BY " .  $orderBy;
    }

    // limit, offset
    $queryStr .= " LIMIT " . $limit . " OFFSET " . $offset;

    //orders
    $query = $dbConn->prepare($queryStr);
    $success = $query->execute();
    $orders = $query->fetchAll(PDO::FETCH_OBJ);

    // totalCount
    $queryStr = "SELECT COUNT(*) FROM `order`";
    if ($filterState) {
      $queryStr .= " WHERE state = '" . $filterState . "'";
    }
    $query = $dbConn->prepare($queryStr);
    $success = $query->execute();
    $totalCount = (int)$query->fetchColumn();

    // preparing response
    foreach ($orders as $index => $item) {
      $item->data = json_decode($item->data);
      $item->sentEmails = json_decode($item->sentEmails);
    }
    $response = ['orders' => $orders, 'totalCount' => $totalCount];
    http_response_code(200);
    echo json_encode($response);
  } catch(Exception $e) {
    InsertError($e, 'WEBAPI-' . __FUNCTION__);
    http_response_code(403);
    echo json_encode(false);
  }
}

function GetOrderByID($id)
{
  try {
    $dbConn = DB::Get();
    $query = $dbConn->prepare("SELECT * FROM `order` where id = ?");
    $query->execute(array((int)$id));
    $item = $query->fetch(PDO::FETCH_OBJ);
    $item->data = json_decode($item->data);
    $item->sentEmails = json_decode($item->sentEmails);
    
    http_response_code(200);
    echo json_encode($item);
  } catch(Exception $e) {
    InsertError($e, 'WEBAPI-' . __FUNCTION__);
    http_response_code(403);
    echo json_encode(false);
  }
}

?>