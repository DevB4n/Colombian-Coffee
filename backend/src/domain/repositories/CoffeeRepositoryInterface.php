<?php

namespace App\domain\repositories;

use App\domain\models\Coffee;

interface CoffeeRepositoryInterface {

    public function getAll(): array;

    public function getById(int $id): ?Coffee;

    public function create(array $data): Coffee;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}