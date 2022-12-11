<?php

require_once 'file-functions.php';

function sendMail($to, $from, $subject, $message) {
    $cssFileContent = $content = file_get_contents("css/mail.css");
    $styleTag = $cssFileContent ?  "<style>" . $cssFileContent . "</style>" : "";
    $mailBody = "<html>
                  <head>
                  <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
                  " . $styleTag . "
                  </head>
                <body>";
    $mailBody .= "\r\n";
    $mailBody .= $message;
    $mailBody .="</body></html>";
    $header = 'MIME-Version: 1.0' . "\r\n";
    $header .= 'Content-Type: text/html; charset=UTF-8' . "\r\n";
    $header .= "From:" . $from . " \r\n";

    return mail($to,$subject,$mailBody,$header);    
}