<?php

use App\controllers\RegisterController;
use App\useCases\RegisterUser;
use App\infrastructure\repositories\UserRepositoryImpl;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use PDO;

$app->post('/register', function (Request $request, Response $response) use ($app) {
    $pdo = $app->getContainer()->get(PDO::class);
    $userRepo = new UserRepositoryImpl($pdo);
    $useCase = new RegisterUser($userRepo);
    $controller = new RegisterController($useCase);

    return $controller->register($request, $response, []);
});
