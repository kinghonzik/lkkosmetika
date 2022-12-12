<?php
  require_once 'model-get.php';
  require_once 'model-insert.php';

  try {
    $docs = GetDocs();
    http_response_code(200);
    echo json_encode($docs);
  
  } catch(Exception $e) {
    InsertError($e, 'WEBAPI-' . __FUNCTION__);
    http_response_code(403);
    echo json_encode(false);
  }
?>