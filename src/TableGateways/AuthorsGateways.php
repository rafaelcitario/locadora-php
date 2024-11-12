<?php

namespace Src\TableGateways;


class AuthorsGateways {
  private $database = null;

  public function __construct(\PDO $database) {
    $this->database = $database;
  }

  public function listAll(): mixed {
    $statement = "
      SELECT * FROM authors
    ";

    try {
      $statement = $this->database->query($statement);
      $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
      return $result;
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  public function list(int $authorId): mixed {
    $statement = "
      SELECT * FROM
      authors
      WHERE id = :id
    ";
    try {
      $statement = $this->database->prepare($statement);
      $statement->execute([
        "id" => (int) $authorId,
      ]);
      $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
      return $result;
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  public function insert(array $input): mixed {
    $statement = "INSER INTO authors (name) VALUES (:name)";
    try {
      $statement = $this->database->prepare($statement);
      $statement->execute([
        "name" => $input['name'],
      ]);
      $result = $statement->rowCount();
      return $result;
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  public function update(int $authorId, array $input): mixed {
    $statement = "UPDATE authors SET COLUMN name = :name WHERE id = :id";
    try {
      $statement = $this->database->prepare($statement);
      $statement->execute([
        "id" => (int) $authorId,
        "name" => $input['name'],
      ]);
      $result = $statement->rowCount();
      return $result;
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  public function delete(int $authorId): mixed {
    $statement = "DELETE FROM authors (name) WHERE id = ?";
    try {
      $statement = $this->database->prepare($statement);
      $statement->execute([
        "id" => (int) $authorId,
      ]);
      $result = $statement->rowCount();
      return $result;
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }
}
