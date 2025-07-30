<?php

namespace App\usesCases;

use App\domain\repositories\CoffeeRepositoryInterface;
use App\domain\models\Coffee;

class GetCoffeeByPropertie
{
    public function __construct(private CoffeeRepositoryInterface $repo) {}

    public function execute(string $propertie, mixed $value): array
    {

        return $this->repo->getByPropertie($propertie, $value);
    }
}
