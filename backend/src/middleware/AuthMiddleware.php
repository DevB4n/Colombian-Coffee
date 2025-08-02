<?php
namespace App\middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Slim\Exception\HttpUnauthorizedException;

class AuthMiddleware
{
    public function __invoke(Request $request, Handler $handler): Response
    {
        $authHeader = $request->getHeaderLine('Authorization');

        if (!$authHeader || !preg_match('/Basic\s+(.*)$/i', $authHeader, $matches)) {
            throw new HttpUnauthorizedException($request, "No se proporcionaron credenciales.");
        }

        $decoded = base64_decode($matches[1]);
        list($user, $pass) = explode(':', $decoded, 2);

        // Credenciales quemadas por ahora
        if ($user !== 'Adrian@gmail.com' || $pass !== 'soylacontra') {
            throw new HttpUnauthorizedException($request, "Credenciales incorrectas.");
        }

        return $handler->handle($request);
    }
}
