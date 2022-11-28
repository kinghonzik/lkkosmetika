<?php 

  require_once 'DB.php';

  function GetConfig()
  {
    $dbConn = DB::Get();
    $query = $dbConn->prepare("SELECT * FROM config");

    $success = $query->execute();

    if(!$success)
    {
      die("SQL query has failed");
    }

    $records = $query->fetch(PDO::FETCH_OBJ);

    return $records;
  }

  function GetProducts()
  {
    $dbConn = DB::Get();
    $query = $dbConn->prepare("SELECT * FROM product");
    $success = $query->execute();

    if(!$success)
    {
      die("SQL query has failed");
    }

    $records = $query->fetchAll(PDO::FETCH_OBJ);

    return $records;
  }

  function InsertBase($dbConn, $data, $tableName, $hasSeoTitle = false) 
  {
      if ($hasSeoTitle) {
        $sql = "INSERT INTO `" . $tableName . "` (`seoTitle`, `data`) VALUES (?, ?)";
        $stmt= $dbConn->prepare($sql);
        $result = $stmt->execute(array($data->seoTitle, $data->data));
      } else {
        $sql = "INSERT INTO `" . $tableName . "` (`data`) VALUES (?)";
        $stmt= $dbConn->prepare($sql);
        $result = $stmt->execute(array($data->data));
      }
  }

  function InsertCategory() 
  {
    try {
      $dbConn = DB::Get();
      $dbConn->beginTransaction();
      $data = json_decode(file_get_contents("php://input"));
      $result = InsertBase($dbConn, $data, 'category', true);

      $dbConn->commit();
      http_response_code(200);
      echo json_encode($dbConn->lastInsertId());

    } catch(Exception $e) {
      $dbConn->rollback();
      http_response_code(403);
      echo json_encode($e);
    }
  }

  function InsertConfig()
  {
    $dbConn = DB::Get();
    $dbConn->beginTransaction();

    try {

      $data = json_decode(file_get_contents("php://input"));


        $sql = "INSERT INTO `config` (`data`) VALUES (?)";
        $stmt= $dbConn->prepare($sql);
        $result = $stmt->execute(array($data));
        $dbConn->commit();

        http_response_code(200);
        echo json_encode('ok');

        if (!$result) {
          throw "Neco se nepovedlo, result je null!";
        }
    } catch(Exception $e) {
      $dbConn->rollback();
      http_response_code(403);
      echo json_encode($e);
    }
  }
