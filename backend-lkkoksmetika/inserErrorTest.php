<?php

require_once 'DB.php';

$dbConn = DB::Get();
$sql = "INSERT INTO `errors` (`type`,`msg`) VALUES (?, ?)";
$stmt = $dbConn->prepare($sql);
$result = $stmt->execute(array('haha', 'test')); 

echo '' . $result;