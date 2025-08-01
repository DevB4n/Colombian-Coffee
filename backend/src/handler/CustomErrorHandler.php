<?php

namespace App\Handler;

use InvalidArgumentException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Interfaces\ErrorHandlerInterface;
use Throwable;

class CustomErrorHandler implements ErrorHandlerInterface
{
    // Inyecta la interfaz ResponseFactoryInterface
    public function __construct(private ResponseFactoryInterface $response) {}

    // Método invocable para manejar excepciones
    public function __invoke(ServerRequestInterface $request, Throwable $exception, bool $displayErrorDetails, bool $logError, bool $logErrorDetails): ResponseInterface
    {
        // Variables por defecto para el manejo de errores
        $status = 500;
        $message = "Error interno en el servidor.";

        // Manejadores específicos de excepciones
        if ($exception instanceof HttpNotFoundException) {
            $status = 404;
            $message = "Ruta no encontrada";
        } elseif ($exception instanceof InvalidArgumentException) {
            $status = 422;
            $message = $exception->getMessage();
        } elseif ($exception instanceof HttpMethodNotAllowedException) {
            $status = 405;
            $message = "Método no permitido";
        }

        // Si se requiere el log de errores, puedes agregar un log aquí
        if ($logError) {
            // Puedes registrar el error en un archivo de logs o una base de datos
            // Log::error($exception->getMessage()); // Ejemplo usando un logger
        }

        // Si en desarrollo, puedes mostrar detalles de errores
        if ($displayErrorDetails) {
            $message = $exception->getMessage();  // Mostrar detalles del error para desarrollo
        }

        // Crear la respuesta con el código de estado y cuerpo en formato JSON
        $response = $this->response->createResponse($status);
        $response->getBody()->write(json_encode(['error' => $message]));

        // Devolver la respuesta con el encabezado JSON
        return $response->withHeader('Content-Type', 'application/json');
    }
}
