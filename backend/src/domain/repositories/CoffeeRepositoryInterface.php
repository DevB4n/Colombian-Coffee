<?php

namespace App\domain\repositories;

use App\domain\models\Coffee;

interface CoffeeRepositoryInterface {

    public function getAll(): array;
    
    public function getByPropertie(string $propertie, mixed $value): array;

    public function getAllByCharacteristic(string $characteristic):array;

    public function deleteByValue(string $characteristic, mixed $value): int;
}