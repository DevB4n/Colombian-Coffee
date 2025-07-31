<?php

namespace App\usesCases;

use App\domain\repositories\CoffeeRepositoryInterface;

class DeleteByValue
{
    public function __construct(private CoffeeRepositoryInterface $repo) {}

    public function execute(string $characteristic, mixed $value): int
    {
        return $this->repo->deleteByValue($characteristic, $value);
    }
}
