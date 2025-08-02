<?php

use App\infrastructure\repositories\UserRepositoryImpl;
use App\useCases\LoginUser;
use App\controllers\LoginController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return function ($app) {
    $app->post('/login', function (Request $request, Response $response) use ($app) {
        $pdo = $app->getContainer()->get(PDO::class);

        $body = $request->getParsedBody();
        $email = $body['email'] ?? '';
        $password = $body['password'] ?? '';

        $userRepo = new UserRepositoryImpl($pdo);
        $loginUseCase = new LoginUser($userRepo);

        try {
            $user = $loginUseCase->execute($email, $password);

            $response->getBody()->write(json_encode([
                'success' => true,
                'usuario' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email
                ]
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }
    });
};
