<?php

namespace Src\Controllers;

class PersonController {
  private $db = null;
  private $userId = null;
  private $requestMethod = null;
  private $personGateway = null;

  public function __construct($database, $userId, $requestMetod) {
    $this->db = $database;
    $this->userId = $userId;
    $this->requestMethod = $requestMetod;


    // $this->personGateway = 
  }

  private function processRequest() {
    switch ($this->requestMethod) {
      case 'GET':
        if ($this->userId) {
          $response = $this->listOne($this->userId);
          break;
        }
        $response = $this->listAll();
        break;
      case 'POST':
        $response = $this->create();
        break;
      case 'PUT':
        $response = $this->update($this->userId);
        break;
      case 'DELETE':
        $response = $this->delete($this->userId);
      default:
        $response = $this->notFound();
        break;
    }

    header($response['status_code_header']);
    if ($response['body']) {
      echo $response['body'];
    }
  }

  private function listOne($userId) {
  }
  private function listAll() {
  }
  private function create() {
  }
  private function update($userId) {
  }
  private function delete($userId) {
  }
}
