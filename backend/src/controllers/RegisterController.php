<?php

namespace App\controllers;

use App\useCases\RegisterUser;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class RegisterController {
    private $registerUser;

    public function __construct(RegisterUser $registerUser) {
        $this->registerUser = $registerUser;
    }

    public function register(Request $request, Response $response, array $args): Response {
        $data = $request->getParsedBody();
        $email = $data['email'] ?? '';
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        try {
            $user = $this->registerUser->execute($email, $username, $password);
            $response->getBody()->write(json_encode([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email
                ]
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }
}
