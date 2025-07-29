<?php

namespace App\controllers;

use App\domain\repositories\CoffeeRepositoryInterface;
use App\usesCases\GetAllCoffee;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CoffeeController
{
    public function __construct(private CoffeeRepositoryInterface $repo) {}

    public function index(Request $request, Response $response): Response
    {
        $useCase = new GetAllCoffee($this->repo);
        $coffee= $useCase->execute();
        $response->getBody()->write(json_encode($coffee));

        return $response;
    }
}