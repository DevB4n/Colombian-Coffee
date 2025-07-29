<?php

namespace App\infrastructure\repositories;

use App\domain\models\Coffee;
use App\domain\repositories\CoffeeRepositoryInterface;

class EloquentCoffeeRepository implements CoffeeRepositoryInterface
{
    public function getAll(): array
    {
        // SELECT * FROM caracteristicas_cafe
        return Coffee::all()->toArray();
    }
}