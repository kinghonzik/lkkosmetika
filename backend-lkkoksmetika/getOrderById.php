<?php
  require_once 'config.php';
  require_once 'model-get.php';

  GetOrderByID((int)$_GET['id']);
?>