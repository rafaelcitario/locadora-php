<?php

namespace Src\Controllers;

use Src\TableGateways\MoviesGateways;

class MoviesControllers {
  private $database;
  private $requestMethod;
  private $movieGateways;
  private $movieId;
  // private $response = [];

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
        $this->notResponse();
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
      return $this->notResponse();
    }
    $response['status_code_header'] = "HTTP/1.1 200 OK";
    $response['body'] = json_encode($result);
    return $response;
  }

  private function getAllMovies() {
    $result = $this->movieGateways->listAll();
    if (!$result) {
      return $this->notResponse();
    }
    $response['status_code_header'] = "HTTP/1.1 200 OK";
    $response['body'] = json_encode($result);
    return $response;
  }

  private function createNew() {
    $input = (array) json_decode(file_get_contents("php://input"), true);
    if (!$this->validateMovie($input)) {
      return $this->unprocessedEntityResponse();
    }
    $this->movieGateways->insert($input);
    $response['status_code_header'] = "HTTP/1.1 201 Created";
    $response['body']               = null;
    return $response;
  }

  private function updateMovie($movieId) {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$this->validateMovie($input)) {
      return $this->unprocessedEntityResponse();
    }
    $this->movieGateways->update($movieId, $input);
    $response['status_code_header'] = "HTTP/1.1 200 OK";
    $response['body']                 = null;
    return $response;
  }

  private function deleteMovie($movieId) {
    $this->movieGateways->delete($movieId);
    $response['status_code_header'] = "HTTP/1.1 200 Sucess!";
    $response['body']                 = null;
    return $response;
  }

  private function unprocessedEntityResponse() {
    $response['status_code_header'] = "HTTP/1.1 402 Unprocessable Entity";
    $response['body']                 = json_encode(["error" => 'Invalid Input',]);
    return $response;
  }

  private function notResponse() {
    $response['status_code_header'] = "HTTP/1.1 404 Not Found!";
    $response['body']                 = null;
    return $response;
  }

  private function validateMovie(array $input) {
    if (!isset($input['movie']) || !isset($input['price']) || !isset($input['category']) || !isset($input['amount'])) {
      return false;
    }
    return true;
  }
}
