<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Illuminate\Database\Capsule\Manager as Capsule;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/Models/Sprint.php';
require __DIR__ . '/../app/Models/RetroItem.php';


$capsule = new Capsule;

$capsule->addConnection([
    'driver' => 'mysql',
    'host' => '127.0.0.1',
    'database' => 'registro_retro_db',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();


$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Microservicio de Retrospectivas funcionando 🚀");
    return $response;
});

$app->get('/sprints', function (Request $request, Response $response) {

    $sprints = Sprint::obtenerTodos();

    $response->getBody()->write(json_encode($sprints));

    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/sprints', function (Request $request, Response $response) {

    $data = json_decode($request->getBody()->getContents(), true);

    Sprint::crear($data);

    $response->getBody()->write(json_encode([
        'mensaje' => 'Sprint creado'
    ]));

    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/items', function (Request $request, Response $response) {

    $data = json_decode($request->getBody()->getContents(), true);

    RetroItem::crear($data);

    $response->getBody()->write(json_encode([
        'mensaje' => 'Item creado'
    ]));

    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/items', function (Request $request, Response $response) {

    $items = RetroItem::obtenerTodos();

    $response->getBody()->write(json_encode($items));

    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/items/{sprint_id}', function (Request $request, Response $response, $args) {

    $items = RetroItem::obtenerPorSprint($args['sprint_id']);

    $response->getBody()->write(json_encode($items));

    return $response->withHeader('Content-Type', 'application/json');
});


$app->run();
