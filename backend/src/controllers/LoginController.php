<?php

namespace App\controllers;

use App\useCases\LoginUser;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class LoginController {
    private $loginUser;

    public function __construct(LoginUser $loginUser) {
        $this->loginUser = $loginUser;
    }

    public function login(Request $request, Response $response, array $args): Response {
        $data = $request->getParsedBody();
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        try {
            $user = $this->loginUser->execute($email, $password);
            $response->getBody()->write(json_encode([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email
                ]
            ]));
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]));
            return $response->withStatus(401);
        }

        return $response;
    }
}
