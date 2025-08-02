<?php

use App\infrastructure\repositories\UserRepositoryImpl;
use App\useCases\LoginUser;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return function ($app) {
    $app->post('/login', function (Request $request, Response $response) {
        $pdo = $this->get(PDO::class);
        $body = $request->getParsedBody();

        $email = $body['email'] ?? '';
        $password = $body['password'] ?? '';

        $userRepo = new UserRepositoryImpl($pdo);
        $loginUseCase = new LoginUser($userRepo);
        $usuario = $loginUseCase->execute($email, $password);

        if ($usuario) {
            $response->getBody()->write(json_encode([
                'success' => true,
                'usuario' => $usuario
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => 'Credenciales inválidas'
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }
    });
};
