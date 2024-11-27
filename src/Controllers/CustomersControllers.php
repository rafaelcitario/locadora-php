<?php


namespace Src\Controllers;

use Src\TableGateways\CustomersGateways;

class CustomersControllers {
  private $idRequest = null;
  private $database = null;
  private $requestMethod = null;
  private $customersGateway = null;

  public function __construct(\PDO $database, string $requestMethod, int | null $id) {
    $this->database = $database;
    $this->requestMethod = $requestMethod;
    $this->idRequest = $id;
    $this->customersGateway = new CustomersGateways($this->database);
  }

  public function processRequest() {
    switch ($this->requestMethod) {
      case 'GET':
        if ($this->idRequest) {
          // $response = $this->getCustomer($this->idRequest);
          break;
        }

        if (!isset($this->idRequest)) {
          $response = $this->getAllCustomers();
          break;
        }
      case 'POST':
        $response = $this->createNew();
        break;
        // case 'PUT':
        //   break;
        // case 'DELETE':
        //   break;
      default:
        $this->statusCodeHeader(400, "Bad Request", null);
        break;
    }

    header($response['status_code_header']);
    if ($response['body']) {
      return $response['body'];
    }
  }

  private function getAllCustomers() {
    $result = $this->customersGateway->listAll();
    if ($result != null) {
      return $this->statusCodeHeader(200, "Success", json_encode($result));
    }
    return $this->statusCodeHeader(400, "Bad Request", null);
  }

  private function getCustomer(int $id) {
  }

  private function createNew() {
    $input = (array) json_decode(file_get_contents("php://input"), true);
    if (!$this->validateCustomer($input)) {
      return $this->statusCodeHeader(422, "Unprocessable Entity", null);
    }
    $this->customersGateway->insert($input);
    return $this->statusCodeHeader(201, "Created", null);
  }



  private function statusCodeHeader(int $code, string $statusMessage, null|bool|string $body) {
    $response['status_code_header'] = "HTTP/1.1 {$code} {$statusMessage}";
    $response['body']                 = $body;
    return $response;
  }

  private function validateCustomer(array $input) {
    if (
      !isset($input["complete_name"]) ||
      !isset($input["cpf"]) ||
      !isset($input["birth_date"]) ||
      !isset($input["phone"]) ||
      !isset($input["address"]) ||
      !isset($input["street"]) ||
      !isset($input["city"]) ||
      !isset($input["country"]) ||
      !isset($input["number"]) ||
      !isset($input["complement"])
    ) {
      return false;
    }

    return true;
  }
}
