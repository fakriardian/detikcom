<?php

include_once './src/config/Request.php';
include_once './src/config/Router.php';
include_once './src/controllers/TransactionController.php';

$router = new Router(new Request);

$router->get('/', function () {
  return <<<HTML
  <h1>Hello world</h1>
HTML;
});


$router->get('/status', function ($request) {
  $lib = new TransactionController();
  return ($request->getBody()) ?
    $lib->getById($request->getBody()) :
    $lib->getAll();
});

$router->post('/create', function ($request) {
  $lib = new TransactionController();
  return $lib->create($request->getBody());
});
