<?php

$url = 'blob:http://localhost:3000/1334d41b-8799-4bb5-83ed-7d9fc1100060';
$url2 = 
'https://media.geeksforgeeks.org/wp-content/uploads/geeksforgeeks-6-1.png'; 

file_put_contents('test.png', file_get_contents($url));



?>