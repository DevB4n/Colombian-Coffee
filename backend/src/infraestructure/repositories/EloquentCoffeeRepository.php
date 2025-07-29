<?php

namespace App\infraestructure\repositories;

use App\domain\models\Coffee;
use App\domain\repositories\CoffeeRepositoryInterface;

class EloquentCoffeeRepository implements CoffeeRepositoryInterface
{
    public function getAll(): array
    {
        // SELECT * FROM caracteristicas_cafe
        return Coffee::all()->toArray();
    }

    public function getByPropertie(string $propertie, mixed $value): ?Coffee
    {
        $response = Coffee::where($propertie,$value)->get()->toArray();
        return $response;
    }
}