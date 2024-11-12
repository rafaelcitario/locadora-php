<?php

namespace Src\Controllers;

use PDOException;
use Src\TableGateways\AuthorsGateways;


class AuthorsControllers {
  private $requestMethod = null;
  private $database      = null;
  private $authorId     = null;
  private $authorsGateways = null;

  public function __construct(\PDO $database, string $requestMethod, int $authorId) {
    $this->database = $database;
    $this->requestMethod = $requestMethod;
    $this->authorId     = $authorId;
    $this->authorsGateways = new AuthorsGateways($this->database);
  }

  public function processRequest() {
    switch ($this->requestMethod) {
      case "GET":
        if ($this->authorId) {
          $response = $this->getAuthor($this->authorId);
          break;
        }

        if (!isset($this->authorId)) {
          $response = $this->getAllAuthor();
          break;
        }
      case "POST":
        $response = $this->createNew();
        break;
      case "PUT":
        $response = $this->updateAuthor($this->authorId);
        break;
      case "DELETE":
        $response = $this->deleteAuthor($this->authorId);
        break;
      default:
        $response = $this->statusCodeHeader(400, "Bad Request!", null);
        break;
    }

    header($response['status_code_header']);
    if ($response['body']) {
      return $response['body'];
    }
  }

  public function getAuthor(int $authorId) {
    try {
      $result = $this->authorsGateways->list($authorId);
      if ($result != null) {
        return $this->statusCodeHeader(200, "Sucess!", json_encode($result));
      }
      return $this->statusCodeHeader(400, "Bad Request!", null);
    } catch (PDOException $e) {
      exit($e->getMessage());
    }
  }
  public function getAllAuthor() {
    try {
      $result = $this->authorsGateways->listAll();
      if ($result != null) {
        return $this->statusCodeHeader(200, "Sucess!", json_encode($result));
      }
      return $this->statusCodeHeader(400, "Bad Request!", null);
    } catch (PDOException $e) {
      exit($e->getMessage());
    }
  }
  public function createNew() {
    try {
      $input = json_decode(file_get_contents('php://input'));
      if (!$this->validateAuthors($input)) {
        return $this->statusCodeHeader(422, "Unprocessable Entity!", json_encode(["error" => "invalid input."]));
      }
      $result = $this->authorsGateways->insert($input);
      return $this->statusCodeHeader(201, "Created!", json_encode($result));
    } catch (PDOException $e) {
      exit($e->getMessage());
    }
  }
  public function updateAuthor(int $authorId) {
    try {
      $input = json_decode(file_get_contents('php://input'));
      if (!$this->validateAuthors($input)) {
        return $this->statusCodeHeader(422, "Unprocessable Entity!", json_encode(["error" => "invalid input"]));
      }
      $result = $this->authorsGateways->update($authorId, $input);
      return $this->statusCodeHeader(201, "Updated!", json_encode($result));
    } catch (PDOException $e) {
      exit($e->getMessage());
    }
  }
  public function deleteAuthor(int $authorId) {
    try {
      $result = $this->authorsGateways->delete($authorId);
      return $this->statusCodeHeader(200, "Deleted!", json_encode($result));
    } catch (PDOException $e) {
      exit($e->getMessage());
    }
  }

  public function validateAuthors(array $input) {
    if (!isset($input['name'])) {
      return false;
    }
    return true;
  }

  public function statusCodeHeader(int $code, string $statusMessage, null | bool | string $body) {
    $response['status_code_header'] = "HTTP/1.1 {$code} $statusMessage";
    $response['body']                 = $body;
    return $response;
  }
}
