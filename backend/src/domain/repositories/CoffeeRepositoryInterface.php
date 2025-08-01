<?php

namespace App\domain\repositories;

use App\domain\models\Coffee;

interface CoffeeRepositoryInterface {

    public function getAll(): array;
    
    public function getByPropertie(string $propertie, mixed $value): array;

    public function getAllByCharacteristic(string $characteristic):array;

    public function deleteFromTableById(string $table, int $id): int;

    public function create(array $data):array;

    public function updateFromTableById(string $table, int $id, array $data): array;
}