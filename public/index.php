<?php

use Src\Controllers\MoviesControllers;

require '../bootstrap.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);
$movieId  = null;
$requestMethod = $_SERVER['REQUEST_METHOD'];
$controller    = (new MoviesControllers($databaseConnection, $requestMethod, $movieId))->processRequest();
$jsonfyController = json_decode($controller, true);


function getMoviesContentByUrl(array $uri, $movieId, $jsonfyController) {
  if ($uri[1] === "") {
    return json_encode([
      "API" => "locadora_php",
      "Owner" => "Rafael Citario",
      "Urls" => json_encode(["/movies", "movies/<some number>"], true)
    ], true);
  }
  if ($uri[1] !== 'movies') {
    header("HTTP/1.1 404 Not Found!");
    return 'This page not exists!';
  }

  if (isset($uri[2])) {
    $movieId = (int) $uri[2];
    return json_encode($jsonfyController[$movieId - 1], true);
  }
  return json_encode($jsonfyController, true);
}
echo getMoviesContentByUrl($uri, $movieId, $jsonfyController);
