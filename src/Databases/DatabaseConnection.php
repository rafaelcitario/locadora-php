<?php

namespace Src\Databases;

class DatabaseConnection {
  private $connectionDatabase = null;

  public function __construct() {
    $HOST_DATABASE = $_ENV['DB_HOST'];
    $PORT_DATABASE     = $_ENV['DB_PORT'];
    $NAME_DATABASE = $_ENV['DB_DATABASE'];
    $USER_DATABASE = $_ENV['DB_USERNAME'];
    $PASSWORD_DATABASE = $_ENV['DB_PASSWORD'];

    try {
      $this->connectionDatabase = new \PDO(
        "mysql:host=$HOST_DATABASE;port=$PORT_DATABASE;charset=utf8mb4;dbname=$NAME_DATABASE",
        $USER_DATABASE,
        $PASSWORD_DATABASE
      );
    } catch (\PDOException $error) {
      exit($error->getMessage());
    }
  }

  public function getConnection() {
    return $this->connectionDatabase;
  }
}
