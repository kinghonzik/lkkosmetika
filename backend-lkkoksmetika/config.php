<?php

class Config
{
   const IMG_FOLDER = '../nuxt3-lkkosmetika/img/products/';
   const SQL_HOST = '127.0.0.1';
   const SQL_DBNAME = 'lkkosmetika';
   const SQL_USERNAME = 'root';
   const SQL_PASSWORD = '';
   public static $authSecret = "2022lk*@#(&)kosmetika^^^tralalala@!juhuuuuu85žral0k";
   public static $authExpirationSeconds = 60 * 60 * 10;
   public static $jwtHeader = array('alg'=>'HS256','typ'=>'JWT');
   public static $CRSFExpirationSec = 60 * 30;
   public static $mailOrdersLink = 'https://lkkosmetika.cz/admin/orders';
}
