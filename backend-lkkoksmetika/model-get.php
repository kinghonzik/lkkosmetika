<?php

require_once 'DB.php';

function GetConfig()
{
  $dbConn = DB::Get();
  $query = $dbConn->prepare("SELECT * FROM config");
  $query->execute();

  $result = $query->fetch(PDO::FETCH_OBJ);
  return json_decode($result->data);
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


function GetProducts()
{
  try {
    $dbConn = DB::Get();
    $products = GetBase($dbConn, 'product');
    http_response_code(200);
    echo json_encode($products);
  } catch(Exception $e) {
    http_response_code(403);
    echo json_encode($e);
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
    http_response_code(403);
    echo json_encode($e);
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
    http_response_code(403);
    echo json_encode($e);
  }
}

function GetOrderByID()
{
  try {
    $id = (int)$_GET['id'];
    $dbConn = DB::Get();
    $query = $dbConn->prepare("SELECT * FROM `order` where id = ?");
    $query->execute(array($id));
    $item = $query->fetch(PDO::FETCH_OBJ);
    $item->data = json_decode($item->data);
    $item->sentEmails = json_decode($item->sentEmails);
    
    http_response_code(200);
    echo json_encode($item);
  } catch(Exception $e) {
    http_response_code(403);
    echo json_encode($e);
  }
}

?>