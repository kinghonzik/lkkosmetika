<?php
  require_once 'config.php';
  require_once 'model-get.php';

  GetOrderByID((int)json_decode($_GET['id']));
?>