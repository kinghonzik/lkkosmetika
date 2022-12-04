<?php

function checkDir($imgFilePath) 
{
  $dirname = dirname($imgFilePath);
  if (!is_dir(Config::IMG_FOLDER . '/' . $dirname))
  {
    $result = mkdir(Config::IMG_FOLDER . '/' . $dirname);
    if(!$result) {
      throw new Exception('Adresář ' . $dirname . ' se nepodařilo vytvořit.');
    }
  }
}

function updateImage($image) 
{
  $image_parts = explode(";base64,", $image->dataBase64String);
  $imgDecoded = base64_decode($image_parts[1]);
  checkDir($image->name);
  file_put_contents(Config::IMG_FOLDER . $image->name, $imgDecoded);
  $image->src = $image->name;
  $image->savedOnServer = true;
  unset($image->dataBase64String);
  unset($image->name);
  $image->status = null;
}