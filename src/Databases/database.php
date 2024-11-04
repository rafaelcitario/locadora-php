<?php


$statement = <<<EOS
  DROP TABLE IF EXISTS customers;
  CREATE TABLE IF NOT EXISTS customers(
  id INT AUTO_INCREMENT NOT NULL,
  complete_name VARCHAR(100) NOT NULL,
  cpf VARCHAR(11) NOT NULL,
  birth_date DATE NOT NULL,
  PRIMARY KEY(id)
  );

  CREATE TABLE IF NOT EXISTS phones(
  id INT AUTO_INCREMENT NOT NULL,
  phone VARCHAR(13) NOT NULL,
  id_customer INT NOT NULL,
  PRIMARY KEY(id),
  FOREIGN KEY(id_customer) REFERENCES customers(id)
  );

  CREATE TABLE IF NOT EXISTS emails(
  id INT AUTO_INCREMENT NOT NULL,
  email VARCHAR(13) NOT NULL,
  id_customer INT NOT NULL,
  PRIMARY KEY(id),
  FOREIGN KEY(id_customer) REFERENCES customers(id)
  );

  CREATE TABLE IF NOT EXISTS addresses(
  id INT AUTO_INCREMENT NOT NULL,
  address VARCHAR(100) NOT NULL,
  street VARCHAR(100) NOT NULL,
  city VARCHAR(100) NOT NULL,
  country VARCHAR(2) NOT NULL,
  number INT(5) NOT NULL
  id_customer INT NOT NULL,
  PRIMARY KEY(id),
  FOREIGN KEY(id_customer) REFERENCES customers(id)
  );

  CREATE TABLE IF NOT EXISTS complements(
  id INT AUTO_INCREMENT NOT NULL,
  complement VARCHAR(5) NOT NULL,
  id_address INT NOT NULL,
  PRIMARY KEY(id),
  FOREIGN KEY(id_address) REFERENCES addresses(id)
  );

  CREATE TABLE IF NOT EXISTS locations(
  id INT AUTO_INCREMENT NOT NULL,
  tax DECIMAL(4,2) NOT NULL,
  id_movie INT NOT NULL,
  id_customer INT NOT NULL,
  PRIMARY KEY(id),
  FOREIGN KEY(id_customer) REFERENCES customers(id),
  FOREIGN KEY(id_movie) REFERENCES movies(id)
  );

  CREATE TABLE IF NOT EXISTS movies(
  id INT AUTO_INCREMENT NOT NULL,
  movie VARCHAR(100) NOT NULL,
  category VARCHAR(10) NOT NULL,
  price DECIMAL(4,2) NOT NULL,
  amount INT NOT NULL,
  PRIMARY KEY(id)
  );

  CREATE TABLE IF NOT EXISTS authors(
  id INT AUTO_INCREMENT NOT NULL,
  name VARCHAR(100) NOT NULL,
  PRIMARY KEY(id)
  );

  CREATE TABLE IF NOT EXISTS movies_authors(
  id INT AUTO_INCREMENT NOT NULL,
  id_movie INT NOT NULL,
  id_author INT NOT NULL,
  PRIMARY KEY(id),
  FOREIGN KEY(id_movie) REFERENCES movies(id),
  FOREIGN KEY(id_author) REFERENCES authors(id)
  );
EOS;

try {
} catch (\PDOException $e) {
  exit("ERROR: " . $e->getMessage());
}
