<?php

require_once "vendor/autoload.php";

use App\infraestructure\database\Connection;
use Slim\Factory\AppFactory;
use Dotenv\Dotenv;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Interfaces\ErrorHandlerInterface;

// Variables de .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/');
$dotenv->load(); // $_ENV[...]

// Se carga el Container de PHP-DI
$container = require_once 'bootstrap/container.php';

// Se asigna a Slim el contenedor
AppFactory::setContainer($container);

// Iniciar la conexion con la db
Connection::init();

$app = AppFactory::create();

// Inyectamos ResponseFactory que necesita nuestro CustomErrorHandler
$container->set(ResponseFactoryInterface::class, $app->getResponseFactory());

// Definir quien va a manejar los errores...
$errorHanlder = $app->addErrorMiddleware(true, true, true);
$errorHanlder->setDefaultErrorHandler($container->get(ErrorHandlerInterface::class));

// Middleware CORS - debe ir ANTES de las rutas
$app->add(function ($request, $handler) {
    $response = $handler->handle($request);

    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
});

$app->options('/{routes:.+}', function ($request, $response) {
    return $response;
});

// Ejecutando los scripts de

// public/
(require_once 'public/index.php')($app);

// routes/
(require_once 'routes/coffees.php')($app);

$app->run();
