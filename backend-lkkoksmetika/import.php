<?php

require_once 'DB.php';
require_once 'import-products-data.php';

  function InsertBase($dbConn, $data, $tableName) 
  {
    $sql = "INSERT INTO `" . $tableName . "` (`data`) VALUES (?)";
    $stmt= $dbConn->prepare($sql);
    $result = $stmt->execute(array(json_encode($data->data)));   
  }


  try {
    $data = GetProductData();
    $decodedArr = json_decode($data);
    
    foreach ($decodedArr as $arrItem) {
        if (!empty($arrItem->additionalImages)) {
          $arrItem->additionalImages = json_decode($arrItem->additionalImages);
          $arr = array();
          foreach ($arrItem->additionalImages as $imgName) {
            $newObject = new stdClass;
            $newObject->src = $imgName;
            $newObject->data = null;
            $newObject->alt = null;
            array_push($arr, $newObject);
          }
          $arrItem->additionalImages = $arr;
        }
        else 
          $arrItem->additionalImages = array();
        if (!empty($arrItem->variants))
          $arrItem->variants = json_decode($arrItem->variants);
        else 
          $arrItem->variants = array();

        if (!empty($arrItem->specifications))
          $arrItem->specifications = json_decode($arrItem->specifications);
        else 
          $arrItem->specifications = array();

        if (!empty($arrItem->categories))
          $arrItem->categories = json_decode($arrItem->categories);
        else 
          $arrItem->categories = array();

        if (!empty($arrItem->usages))
          $arrItem->usages = json_decode($arrItem->usages);
        else 
          $arrItem->usages = array();

        $dbConn = DB::Get();
        $tableName = 'product';
        $sql = "INSERT INTO `" . $tableName . "` (`data`) VALUES (?)";
        $stmt= $dbConn->prepare($sql);
        $result = $stmt->execute(array(json_encode($arrItem)));  
    }

    var_dump("OK");


    } catch(Exception $e) {
      echo var_dump($e);
    }

  

  ?>