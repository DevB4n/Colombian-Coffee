<?php

namespace App\usesCases;

use App\domain\repositories\CoffeeRepositoryInterface;

class DeleteFromTableById
{
    public function __construct(private CoffeeRepositoryInterface $repo) {}

    public function execute(string $table, int $id): int
    {
        return $this->repo->deleteFromTableById($table, $id);
    }
}