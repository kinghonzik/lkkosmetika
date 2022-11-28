<?php
  require_once 'config.php';
  require_once 'model-get.php';

try {
  $obj = new stdClass;
  $obj->config = GetConfig();
  $obj->manufacturers = GetManufacturers();
  $obj->categories = GetCategiries();
  $obj->usages = GetUsages();

  http_response_code(200);
  echo json_encode($obj);

} catch(Exception $e) {
  http_response_code(403);
  echo json_encode($e);
}
?>