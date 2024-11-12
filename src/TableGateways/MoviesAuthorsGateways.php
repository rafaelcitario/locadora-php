<?php

namespace Src\TableGateways;

use Exception;
use \PDO;

class MoviesAuthorsGateways {
  private $database = null;

  public function __construct(PDO $database) {
    $this->database = $database;
  }


  public function list(int $id) {
    $statement = "
    SELECT movie, category, price, amount, name as 'author' FROM
    movies INNER JOIN(
    movies_authors INNER JOIN authors
    ON authors.id = movies_authors.id_author)
    ON movies.id = movies_authors.id_movie
    WHERE movies.id = :id;
    ";

    $statement = $this->database->prepare($statement);
    $statement->execute([
      "id" => (int) $id,
    ]);
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    return $result;
  }

  public function listAll() {
    $statement = "
    SELECT movies.id, movie, category, price, amount, GROUP_CONCAT(authors.name SEPARATOR ', ') as authors FROM
    movies INNER JOIN(
    movies_authors INNER JOIN authors
    ON authors.id = movies_authors.id_author)
    ON movies.id = movies_authors.id_movie
    group by movies.id;
    ";

    $statement = $this->database->query($statement);
    $result    = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }

  public function insert(array $input) {
    $this->database->beginTransaction();
    try {
      $statement = "INSERT INTO movies 
      (movie, category, price, amount)
      VALUES
      (:movie, :category, :price, :amount);";

      $statement = $this->database->prepare($statement);
      $statement->execute([
        'movie' => $input['movie'],
        'category' => $input['category'],
        'price' => $input['price'],
        'amount' => $input['amount'],
      ]);

      $movieId = $this->database->lastInsertId();

      $authors = explode(',', $input['name']);
      $authorsIds = [];

      foreach ($authors as $author) {
        $author    = trim($author);
        $statement = "
        INSERT INTO authors (name) VALUES (:name);";

        $statement = $this->database->prepare($statement);
        $statement->execute([
          'name' => $author,
        ]);

        $authorsIds[] = $this->database->lastInsertId();
      }
      foreach ($authorsIds as $authorId) {
        $statement = "INSERT INTO movies_authors (id_movie, id_author) VALUES (:id_movie, :id_author)";
        $statement = $this->database->prepare($statement);
        $statement->execute([
          'id_movie' => (int) $movieId,
          'id_author' => (int) $authorId,
        ]);
        var_dump($authorId);
      }

      $result = $this->database->commit();
      return $result;
    } catch (Exception $e) {
      $this->database->rollBack();
      throw $e;
    }
  }
  public function update(int $idToUpdate, array $input) {
  }
  public function delete(int $idToDelete) {
  }
}
