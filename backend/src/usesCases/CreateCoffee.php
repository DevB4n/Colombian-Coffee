<?php

namespace App\usesCases;

use App\domain\repositories\CoffeeRepositoryInterface;
use App\domain\models\Coffee;

class CreateCoffee
{
    public function __construct(private CoffeeRepositoryInterface $repo) {}

    public function execute(array $data): array
    {
        return $this->repo->create($data);
    }
}
