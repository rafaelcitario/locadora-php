<?php

require 'vendor/autoload.php';

use Dotenv\Dotenv;
use Src\Databases\DatabaseConnection;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
$databaseConnection = (new DatabaseConnection())->getConnection();
