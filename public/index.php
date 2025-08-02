<?php

use Slim\Factory\AppFactory;
use App\middleware\JsonBodyParserMiddleware;
use DI\Container;

require __DIR__ . '/../vendor/autoload.php';

// Crear contenedor de dependencias
$container = new Container();
AppFactory::setContainer($container);

// Crear instancia de Slim
$app = AppFactory::create();

// Registrar middlewares globales
(require __DIR__ . '/../backend/public/index.php')($app); 

// el registro de nuestras rutas
(require __DIR__ . '/../backend/routes/coffees.php')($app);
(require __DIR__ . '/../backend/routes/login.php')($app);


$app->run();
