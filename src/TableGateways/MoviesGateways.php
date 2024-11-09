<?php

namespace Src\TableGateways;

class MoviesGateways {
  private $database = null;
  public function __construct($database) {
    $this->database = $database;
  }

  public function listAll() {
    $statement = "
      SELECT 
      id, movie, category, price, amount
      FROM 
      movies;
    ";

    try {
      $statement = $this->database->query($statement);
      $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
      return $result;
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  public function list(int $id) {
    $statement = "
    SELECT 
    id, movie, category, price, amount  
    FROM movies
    WHERE 
    id LIKE :id;
    ";
    try {
      $statement = $this->database->prepare($statement);
      $statement->execute(array(
        'id' => (int) $id,
      ));
      $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
      return $result;
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  public function insert(array $input) {
    $statement = "
    INSERT INTO movies 
    (movie, category, price, amount) 
    VALUES 
    (:movies, :category, :price, :amount);
    ";

    try {
      $statement = $this->database->prepare($statement);
      $statement->execute(array(
        'movies' => $input['movies'],
        'category' => $input['category'],
        'price' => $input['price'],
        'amount' => $input['amount'],
      ));
      $result = $statement->rowCount();
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  public function update(int $id, array $input) {
    $statement = "
    UPDATE movies
    SET 
      movie = :movie,
      category = :category,
      price = :price,
      amount = :amount
    WHERE id = :id;
    ";

    try {
      $statement = $this->database->prepare($statement);
      $statement->execute(array(
        'id' => (int) $id,
        'movie' => $input['movie'],
        'category' => $input['category'],
        'price' => $input['price'],
        'amount' => $input['amount'],
      ));
      $result = $statement->rowCount();
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  public function delete(int $id) {
    $statement = "
    DELETE FROM 
    movies
    WHERE id = :id;
    ";

    try {
      $statement = $this->database->prepare($statement);
      $statement->execute(array(
        'id' => (int) $id,
      ));
      $result = $statement->rowCount();
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }
}
