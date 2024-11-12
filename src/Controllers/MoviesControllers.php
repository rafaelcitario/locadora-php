<?php

namespace Src\Controllers;

use Src\TableGateways\MoviesGateways;

class MoviesControllers {
  private $database;
  private $requestMethod;
  private $movieGateways;
  private $movieId;

  public function __construct($database, $requestMethod, $movieId) {
    $this->requestMethod = $requestMethod;
    $this->movieId       = $movieId;
    $this->database      = $database;
    $this->movieGateways = new MoviesGateways($this->database);
  }

  public function processRequest() {
    switch ($this->requestMethod) {
      case 'GET':
        if ($this->movieId) {
          $response = $this->getMovie($this->movieId);
          break;
        }

        if (!$this->movieId) {
          $response = $this->getAllMovies();
          break;
        }
      case 'POST':
        $response = $this->createNew();
        break;
      case 'PUT':
        $response = $this->updateMovie($this->movieId);
        break;
      case 'DELETE':
        $response = $this->deleteMovie($this->movieId);
        break;
      default:
        $this->statusCodeHeader(400, "Bad Request!", null);
        break;
    }

    header($response['status_code_header']);
    if ($response['body']) {
      return $response['body'];
    }
  }

  private function getMovie(int $movieId) {
    $result =  $this->movieGateways->list($movieId);
    if (!$result) {
      return $this->statusCodeHeader(400, "Bad Request!", null);
    }
    return $this->statusCodeHeader(200, "Sucess!", json_encode($result));
  }

  private function getAllMovies() {
    $result = $this->movieGateways->listAll();
    if (!$result) {
      return $this->statusCodeHeader(400, "Bad Request!", null);
    }
    return $this->statusCodeHeader(200, "Sucess!", json_encode($result));
  }

  private function createNew() {
    $input = (array) json_decode(file_get_contents("php://input"), true);
    if (!$this->validateMovie($input)) {
      return $this->statusCodeHeader(422, "Unprocessable Entity!", json_encode(["error" => 'Invalid Input',]));
    }
    $this->movieGateways->insert($input);
    return $this->statusCodeHeader(201, "Created!", null);
  }

  private function updateMovie($movieId) {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$this->validateMovie($input)) {
      return $this->statusCodeHeader(422, "Unprocessable Entity!", json_encode(["error" => 'Invalid Input',]));
    }
    $this->movieGateways->update($movieId, $input);
    return $this->statusCodeHeader(200, "Sucess!", null);
  }

  private function deleteMovie($movieId) {
    $this->movieGateways->delete($movieId);
    return $this->statusCodeHeader(200, "Sucess!", null);
  }

  public function statusCodeHeader(int $code, string $statusMessage, null | bool | string $body) {
    $response['status_code_header'] = "HTTP/1.1 {$code} $statusMessage";
    $response['body']                 = $body;
    return $response;
  }

  private function validateMovie(array $input) {
    if (!isset($input['movie']) || !isset($input['price']) || !isset($input['category']) || !isset($input['amount'])) {
      return false;
    }
    return true;
  }
}
