<?php

namespace App\Http\Traits;

use Psr\Http\Message\ResponseInterface as Response;

trait ApiResponserTrait
{
    protected function successResponse(Response $response, $data, int $httpResponseCode = 200): Response
    {
        $response->getBody()->write(json_encode([
            'success'    => true,
            'message'    => null,
            'data'       => $data,
            'errors'     => null,
        ]));
        return $response->withStatus($httpResponseCode);
    }

    protected function errorResponse(Response $response, string $message, ?array $errors = [], int $httpResponseCode = 400): Response
    {
        $response->getBody()->write(json_encode([
            'success'    => true,
            'message'    => $message ?? null,
            'data'       => null,
            'errors'     => $errors ?? null,
        ]));
        return $response->withStatus($httpResponseCode)->withHeader('Content-Type', 'application/json');
    }
}