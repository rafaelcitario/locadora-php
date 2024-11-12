<?php

namespace Src\Controllers;

use Src\TableGateways\MoviesAuthorsGateways;

class MoviesAuthorsControllers {
  private $database               = null;
  private $idRequest              = null;
  private $requestMethod          = null;
  private $movies_authorsGateways = null;

  public function __construct(\PDO $database, string $requestMethod, int | null $id) {
    $this->database = $database;
    $this->requestMethod = $requestMethod;
    $this->idRequest     = $id;
    $this->movies_authorsGateways = new MoviesAuthorsGateways($this->database);
  }

  public function processRequest() {
    switch ($this->requestMethod) {
      case 'GET':
        if ($this->idRequest) {
          $response = $this->list($this->idRequest);
          break;
        }

        if (!isset($this->idRequest)) {
          $response = $this->listAll();
          break;
        }
      case 'POST':
        $response = $this->insert();
        break;
      case 'PUT':
        // $response = $this->update();
        break;
      case 'DELETE':
        // $response = $this->delete();
        break;
      default:
        $response = $this->statusCodeHeader(400, "Bad Request", null);
        break;
    }

    header($response['status_code_header']);
    if ($response['body']) {
      return $response['body'];
    }
  }

  public function list(int $id) {
    $result = $this->movies_authorsGateways->list($id);
    if ($result !== null) {
      return $response = $this->statusCodeHeader(200, 'Sucess', json_encode($result));
    }
    return $response = $this->statusCodeHeader(404, 'Not Found', null);
  }

  public function listAll() {
    $result = $this->movies_authorsGateways->listAll();
    if ($result !== null) {
      return $response = $this->statusCodeHeader(200, 'Sucess', json_encode($result));
    }
    return $response = $this->statusCodeHeader(404, 'Not Found', null);
  }
  public function insert() {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$this->validadeInputRequest($input)) {
      return $this->statusCodeHeader(422, "Unprocessable Entity", null);
    }
    $result = $this->movies_authorsGateways->insert($input);
    return $this->statusCodeHeader(201, "Created", json_encode($result));
  }
  // public function update(int $idToUpdate, array $input) {
  //   $result = $this->movies_authorsGateways->update();
  //   if ($result !== null) {
  //     return $response = $this->statusCodeHeader(200, 'Sucess', json_encode($result));
  //   }
  //   return $response = $this->statusCodeHeader(404, 'Not Found', null);
  // }
  // public function delete(int $idToDelete) {
  //   $result = $this->movies_authorsGateways->delete();
  //   if ($result !== null) {
  //     return $response = $this->statusCodeHeader(200, 'Sucess', json_encode($result));
  //   }
  //   return $response = $this->statusCodeHeader(404, 'Not Found', null);
  // }

  public function validadeInputRequest(array $input) {
    if (!isset($input['movie']) || !isset($input['category']) || !isset($input['price']) || !isset($input['amount']) || !isset($input['name'])) {
      return false;
    }
    return true;
  }

  public function statusCodeHeader(int $code, string $statusMessage, null | bool | string $body) {
    $response['status_code_header'] = "HTTP/1.1 {$code} {$statusMessage}!";
    $response['body'] = $body;
    return $response;
  }
}
