<?php

namespace App\usesCases;

use App\domain\repositories\CoffeeRepositoryInterface;

class GetAllCoffee
{
    public function __construct(private CoffeeRepositoryInterface $repo) {}

    public function execute(): array
    {
        return $this->repo->getAll();
    }
}