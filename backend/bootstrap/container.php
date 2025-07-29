<?php

use App\domain\repositories\CoffeeRepositoryInterface;
use App\infrastructure\repositories\EloquentCoffeeRepository;
use App\handler\CustomErrorHandler;
use DI\Container;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Interfaces\ErrorHandlerInterface;

// Clase a reemplazar y valor creado a recibir
$container = new Container();

$container->set(CoffeeRepositoryInterface::class, function() {
    return new EloquentCoffeeRepository();
});

$container->set(ErrorHandlerInterface::class, function() use ($container) {
    return new CustomErrorHandler(
        $container->get(ResponseFactoryInterface::class)
    );
});

return $container;