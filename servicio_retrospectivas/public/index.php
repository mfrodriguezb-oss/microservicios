<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Illuminate\Database\Capsule\Manager as Capsule;

require __DIR__ . '/../vendor/autoload.php';

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

    $sprints = Capsule::table('sprints')->get();

    $response->getBody()->write(json_encode($sprints));

    return $response->withHeader('Content-Type', 'application/json');
});
$app->post('/sprints', function (Request $request, Response $response) {

    $data = json_decode($request->getBody()->getContents(), true);

    Capsule::table('sprints')->insert([
        'nombre' => $data['nombre'],
        'fecha_inicio' => $data['fecha_inicio'],
        'fecha_fin' => $data['fecha_fin']
    ]);

    $response->getBody()->write(json_encode([
        'mensaje' => 'Sprint creado'
    ]));

    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/items', function (Request $request, Response $response) {

    $data = json_decode($request->getBody()->getContents(), true);

    Capsule::table('retro_items')->insert([
        'sprint_id' => $data['sprint_id'],
        'categoria' => $data['categoria'],
        'descripcion' => $data['descripcion'],
        'cumplida' => $data['cumplida'] ?? null,
        'fecha_revision' => $data['fecha_revision'] ?? null
    ]);

    $response->getBody()->write(json_encode([
        'mensaje' => 'Item creado'
    ]));

    return $response->withHeader('Content-Type', 'application/json');

});
$app->get('/items', function (Request $request, Response $response) {

    $items = Capsule::table('retro_items')->get();

    $response->getBody()->write(json_encode($items));

    return $response->withHeader('Content-Type', 'application/json');

});

$app->run();

