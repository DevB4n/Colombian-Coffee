<?php

namespace App\usesCases;

use App\domain\repositories\CoffeeRepositoryInterface;

class UpdateCoffee
{
    public function __construct(private CoffeeRepositoryInterface $repo) {}

    public function execute(string $table, int $id, array $data): array
    {
        return $this->repo->updateFromTableById($table, $id, $data);
    }
}
