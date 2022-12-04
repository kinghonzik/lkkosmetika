<?php

require_once 'DB.php';

try 
{
    $path = 'test/file-test.txt';
    $dirname = dirname($path);
    var_dump($dirname);

    if (!is_dir(Config::IMG_FOLDER . '/' . $dirname))
    {
        $result = mkdir(Config::IMG_FOLDER . '/' . $dirname);
        if($result) {
            throw('Adresář ' . $dirname . ' se nepodařilo vytvořit.')
        }
    }
    file_put_contents(Config::IMG_FOLDER . $path, 'test-str');
}catch (Exception $e)
{
    var_dump($e);
}