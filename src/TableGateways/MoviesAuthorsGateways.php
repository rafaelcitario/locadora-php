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
  public function update(int $movieId, array $input) {
    /**
     * Explicando raciocinio para atualização:
     * primeiro atualizamos os dados do filme
     * assim que finalizamos a atualização do filme
     * criamos um array separando todos os nomes de autores que serão passados
     * dentro do input de author. e.g Christofer Nola, R.R. Martin
     * criamos também um array vazio que ira receber os ids dos autores
     * 
     * inicializamos um foreach pois queremos interagir com todos os elementos
     * do impout author
     * executamos um trim para retirar os espacos em branco de cada nome separado por virgulas
     * e.g Christofer Nola, R.R. Martin => ['Christofer Nola', 'R.R. Martin']
     * 
     * verificamos se cada nome passado ja existe dentro de nossa tabela authors
     * caso o nome ja exista então passamos o id deste author para dentro daquele array que tinhamos criado anteriormente
     * caso o nome não exista em nossa tabela, inserimos.
     * 
     * já fora do foreach executamos uma deleção dos dados na tabela relacional
     * para que retiremos todos os autores relacionados ao id de um determinado filme
     * e logo apos inserimos os novos authores.
     * desta forma apagamos os dados e impossibilitamos de existir dados duplicados
     * alem de inserir dados novos a tabela de relacionamento.
     */
    $this->database->beginTransaction();
    try {
      $statement = "
      UPDATE movies
      SET movies = :movie, category = :category, price = :price, amount = :amount
      WHERE id = :id";

      $statement = $this->database->prepare($statement);
      $statement->execute([
        "id" => $movieId,
        "movie" => $input['movie'],
        "category" => $input['category'],
        "price" => $input['price'],
        "amount" => $input['amount'],
      ]);

      $authors = explode(',', $input['name']);
      $authorsIds = [];

      foreach ($authors as $author) {
        $author = trim($author);

        $statement = "SELECT id FROM authors WHERE name = :name LIMIT 1";
        $statement = $this->database->prepare($statement);
        $statement->execute([
          "name" => $author,
        ]);
        $authorExists = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ($authorExists) {
          $authorsIds[] = $authorExists['id'];
        } else {
          $statement = "INSERT INTO authors (name) VALUES (:name)";
          $statement = $this->database->prepare($statement);
          $statement->execute([
            "name" => $author,
          ]);
          $authorsIds[] = $this->database->lastInsertId();
        }
      }

      $statement = "DELETE FROM movies_authors WHERE id_movie = :id_movie";
      $statement = $this->database->prepare($statement);
      $statement->execute([
        "id_movie" => $movieId,
      ]);

      foreach ($authorsIds as $authorId) {
        $statement = "INSERT INTO movies_authors (id_movie, id_author) VALUES (:id_movie, :id_author)";
        $statement = $this->database->prepare($statement);
        $statement->execute([
          "id_movie" => $movieId,
          "id_author" => $authorId,
        ]);
      }
      $this->database->commit();
      return true;
    } catch (Exception $e) {
      $this->database->rollBack();
      throw $e;
    }
  }
  public function delete(int $movieId) {
    $this->database->beginTransaction();
    try {
      $statement = "DELETE FROM movies_authors WHERE id_movie = :id_movie";
      $statement = $this->database->prepare($statement);
      $statement->execute([
        "id_movie" => (int) $movieId,
      ]);

      $statement = "DELETE FROM movies WHERE id = :id";
      $statement = $this->database->prepare($statement);
      $statement->execute([
        "id" => (int) $movieId,
      ]);
      $this->database->commit();
      return true;
    } catch (Exception $e) {
      $this->database->rollBack();
      throw $e;
    }
  }
}
