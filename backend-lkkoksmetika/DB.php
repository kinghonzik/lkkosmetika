<?php
require_once 'config.php';

class DB
{
  protected static $connection = null;

  protected static $connectionOrderDb = null;

  protected static function Create()
  {
    $dsn = 'mysql:dbname=' . Config::SQL_DBNAME . ';host=' . Config::SQL_HOST . '';
    $user = Config::SQL_USERNAME;
    $password = Config::SQL_PASSWORD;

    try {
      DB::$connection = new PDO($dsn, $user, $password);
      DB::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      DB::$connection->exec("SET CHARACTER SET utf8");
    } catch (PDOException $e) {
        die('DB connection failed: ' . $e->getMessage());
    }
  }

  public static function Get()
  {
    if (DB::$connection === null)
    {
      DB::Create();
    }

    return DB::$connection;
  }

  protected static function CreateOrderDb()
  {
    $dsn = 'mysql:dbname=' . ConfigOrderDb::SQL_DBNAME . ';host=' . ConfigOrderDb::SQL_HOST . '';
    $user = ConfigOrderDb::SQL_USERNAME;
    $password = ConfigOrderDb::SQL_PASSWORD;

    try {
      DB::$connectionOrderDb = new PDO($dsn, $user, $password);
      DB::$connectionOrderDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      DB::$connectionOrderDb->exec("SET CHARACTER SET utf8");
    } catch (PDOException $e) {
        die('OrderDB connection failed: ' . $e->getMessage());
    }
  }

  public static function GetOrderDb()
  {
    if (DB::$connectionOrderDb === null)
    {
      DB::CreateOrderDb();
    }

    return DB::$connectionOrderDb;
  }
}

