<?php

namespace App\controllers;

use App\domain\repositories\CoffeeRepositoryInterface;
use App\usesCases\UpdateCoffee;
use App\usesCases\CreateCoffee;
use App\usesCases\DeleteFromTableById;
use App\usesCases\GetAllCoffee;
use App\usesCases\GetCoffeeByPropertie;
use App\usesCases\GetAllByCharactheristic;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CoffeeController
{
    public function __construct(private CoffeeRepositoryInterface $repo) {}

    public function index(Request $request, Response $response): Response
    {
        $useCase = new GetAllCoffee($this->repo);
        $coffee = $useCase->execute();

        if(!$coffee) {
            $response->getBody()->write(json_encode(["error" => "No hay datos registrados"]));
            return $response->withStatus(404);
        }
        $response->getBody()->write(json_encode($coffee));

        return $response;
    }

    public function getByPropertie(Request $request, Response $response): Response
    {
        $queryParams = $request->getQueryParams();
        $propertie = $queryParams['propertie'] ?? null;
        $value = $queryParams['value'] ?? null;

        if (!$propertie || $value === null) {
            $response->getBody()->write(json_encode([
                "error" => "Faltan parámetros: 'propertie' y 'value' son requeridos"
            ]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $useCase = new GetCoffeeByPropertie($this->repo);
        $coffees = $useCase->execute($propertie, $value);

        if (empty($coffees)) {
            $response->getBody()->write(json_encode([
                "error" => "No se encontró ningun cafe con esa propiedad"
            ]));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode($coffees));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getByCharacteristic(Request $request, Response $response): Response
    {
        $queryParams = $request->getQueryParams();
        $characteristic = $queryParams['characteristic'] ?? null;
        $valueFilter = $queryParams['value'] ?? null;

        if (!$characteristic) {
            $response->getBody()->write(json_encode([
                "error" => "Falta el parametro 'characteristic'"
            ]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $useCase = new GetAllByCharactheristic($this->repo);
        $data = $useCase->getAllByCharacteristic($characteristic);

        // 🟨 Aplica filtro si viene 'value'
        if ($valueFilter !== null) {
            $data = array_filter($data, function ($item) use ($characteristic, $valueFilter) {
                return isset($item[$characteristic]) && $item[$characteristic] == $valueFilter;
            });
            $data = array_values($data); // Reindexar
        }

        if (empty($data)) {
            $response->getBody()->write(json_encode([
                "error" => "No se encontraron resultados con esa caracteristica/valor"
            ]));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function deleteFromTable(Request $request, Response $response): Response
    {
        $params = $request->getQueryParams();
        $table = $params['table'] ?? null;
        $id = $params['id'] ?? null;

        if (!$table || !$id || !is_numeric($id)) {
            $response->getBody()->write(json_encode([
                "error" => "Parametros requeridos: 'table' y 'id'"
            ]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        try {
            $useCase = new \App\usesCases\DeleteFromTableById($this->repo);
            $deleted = $useCase->execute($table, (int)$id);

            if ($deleted === 0) {
                $response->getBody()->write(json_encode([
                    "message" => "No se encontro el registro en '$table' con ID $id"
                ]));
                return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
            }

            $response->getBody()->write(json_encode([
                "message" => "Registro eliminado de '$table' con ID $id"
            ]));
            return $response->withHeader('Content-Type', 'application/json');

        } catch (\InvalidArgumentException $e) {
            $response->getBody()->write(json_encode([
                "error" => $e->getMessage()
            ]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                "error" => "Error al eliminar: " . $e->getMessage()
            ]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    public function create(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        if(!$data| !is_array($data)) {
            $response->getBody()->write(json_encode([
                "error" => "Datos invalidos o faltantes en la solicitud"
            ]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        try {
            $useCase = new CreateCoffee($this->repo);
            $coffee = $useCase->execute($data);

            $response->getBody()->write(json_encode([
                "message" => "Datos insertados correctamente",
                "data" => $coffee
            ]));

            return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
            
        } catch (Exception $e) {
            $response->getBody()->write(json_encode([
                "error" => "Error al crear: " . $e->getMessage()
            ]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

}