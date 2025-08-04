<?php

namespace App\Controllers;

use App\Domain\Repositories\UserRepositoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\DTOs\UserDTO;
use App\Http\Traits\ApiResponserTrait;

class UserController
{
    use ApiResponserTrait;

    public function __construct(private UserRepositoryInterface $repo) {}

    public function createUser(Request $request, Response $response): Response
    {

        $data = $request->getParsedBody();
        //TODO: Se debe implementar con Caso de USOOOOOOO!
        $dto = new UserDTO(
            nombre: $data['nombre'] ?? '',
            email: $data['correo'] ?? '',
            password: $data['contraseña'] ?? '',
            rol: 'user',
        );

        $user = $this->repo->create($dto);

        return $this->successResponse($response, $user, 201);
    }

    public function createAdmin(Request $request, Response $response): Response
    {
        //TODO: Se debe implementar con Caso de USOOOOOOO!
        $data = $request->getParsedBody();
        $data['rol'] = 'admin';
        //DTO
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $user = $this->repo->create($data);

        return $this->successResponse($response, $user, 201);
    }
}