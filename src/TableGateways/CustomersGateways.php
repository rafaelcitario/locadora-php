<?php


namespace Src\TableGateways;

use Exception;

class CustomersGateways {
  private $database;
  public function __construct(\PDO $database) {
    $this->database = $database;
  }

  public function insert(array $input) {
    $this->database->beginTransaction();

    try {
      $statement = "INSERT INTO customers 
      (complete_name, cpf, birth_date) 
      VALUES 
      (:complete_name, :cpf, :birth_date);";

      $statement = $this->database->prepare($statement);
      $statement->execute([
        "complete_name" => $input['complete_name'],
        "cpf" => $input['cpf'],
        "birth_date" => $input['birth_date']
      ]);

      $customerId = $this->database->lastInsertId();

      $select = "SELECT * FROM customers where id = $customerId";
      $select = $this->database->prepare($select);
      $result = $select->fetchAll(\PDO::FETCH_ASSOC);

      var_dump($result, $customerId);

      $emails = explode(',', $input['email']);
      $emailsId = [];

      foreach ($emails as $email) {
        $email = trim($email);

        $statement = "
        INSERT INTO emails
        (email, id_customer)
        VALUES
        (:email, :id_customer)
      ";

        $statement = $this->database->prepare($statement);
        $statement->execute([
          "email" => $email,
          "id_customer" => $customerId
        ]);

        $emailsId[] = $this->database->lastInsertId();
      }

      $phones = explode(',', $input['phone']);
      $phoneId = [];

      foreach ($phones as $phone) {
        $phone = trim($phone);
        $statement = "
        INSERT INTO phones
        (phone, id_customer)
        VALUES
        (:phone, :id_customer)
        ";

        $statement = $this->database->prepare($statement);
        $statement->execute([
          "phone" => $phone,
          "id_customer" => $customerId,
        ]);

        $phoneId[] = $this->database->lastInsertId();
      }

      $statement = "
        INSERT INTO addresses
        (address, street, city, country, number, id_customer)
        VALUES
        (:address, :street, :city, :country, :number, :id_customer)
      ";
      $statement = $this->database->prepare($statement);
      $statement->execute([
        "address" => $input['address'],
        "street" => $input['street'],
        "city" => $input['city'],
        "country" => $input['country'],
        "number" => $input['number'],
        "id_customer" => $customerId,
      ]);

      $addressId = $this->database->lastInsertId();

      $statement = "
      INSERT INTO complements
      (complement, id_address)
      VALUES
      (:complement, :id_address)
      ";
      $statement = $this->database->prepare($statement);
      $statement->execute([
        "complement" => $input['complement'],
        "id_address" => $addressId,
      ]);

      $complementsId = $this->database->lastInsertId();
      $result        = $this->database->commit();
      return $result;
    } catch (Exception $e) {
      $this->database->rollBack();
      throw $e;
    }
  }
}
