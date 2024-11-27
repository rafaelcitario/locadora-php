<?php

use Src\Controllers\AuthorsControllers;
use Src\Controllers\MoviesAuthorsControllers;
use Src\Controllers\MoviesControllers;
use Src\Controllers\CustomersControllers;

require '../bootstrap.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

$requestMethod = $_SERVER['REQUEST_METHOD'];

$movieId = null;
$authorId = null;
$movieInfoId = null;

if ($uri[1] === 'movies') {
  if (isset($uri[2])) {
    $movieId = (int) $uri[2];
  }
  $moviesControllers = (new MoviesControllers($databaseConnection, $requestMethod, $movieId))->processRequest();
  $encodedMovies     = json_encode($moviesControllers);
  echo json_decode($encodedMovies, true);
}
if ($uri[1] === "authors") {
  if (isset($uri[2])) {
    $authorId = (int) $uri[2];
  }
  $authorsControllers = (new AuthorsControllers($databaseConnection, $requestMethod, $authorId))->processRequest();
  $encodedAuthors     = json_encode($authorsControllers);
  echo json_decode($encodedAuthors, true);
}

if ($uri[1] === "moviesInfo") {
  if (isset($uri[2])) {
    $movieInfoId = (int) $uri[2];
  }
  $moviesAuthorsControllers = (new MoviesAuthorsControllers($databaseConnection, $requestMethod, $movieInfoId))->processRequest();
  $encodedMoviesInfo = json_encode($moviesAuthorsControllers);
  echo json_decode($encodedMoviesInfo, true);
}

if ($uri[1] === "customer") {
  if (isset($uri[2])) {
    $customerId = (int) $uri[2];
  }
  $customersControllers = (new CustomersControllers($databaseConnection, $requestMethod, $movieInfoId))->processRequest();
  $encodedCustomers = json_encode($customersControllers);
  echo json_decode($encodedCustomers, true);
}
