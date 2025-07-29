<?php

namespace App\domain\repositories;

use App\domain\models\Coffee;

interface CoffeeRepositoryInterface {

    public function getAll(): array;
    
    public function getByPropertie(string $propertie, mixed $value): array;

}