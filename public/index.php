<?php

use Src\Controllers\AuthorsControllers;
use Src\Controllers\MoviesControllers;

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
