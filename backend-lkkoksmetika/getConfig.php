<?php
  require_once 'config.php';
  require_once 'model-get.php';


  try {
    $config = GetConfig();
    http_response_code(200);
    echo json_encode($config);
  
  } catch(Exception $e) {
    http_response_code(403);
    echo json_encode($e);
  }
?>