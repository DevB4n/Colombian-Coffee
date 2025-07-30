<?php

namespace App\usesCases;
use App\domain\repositories\CoffeeRepositoryInterface;

class GetAllByCharactheristic{
    
    public function __construct(private CoffeeRepositoryInterface $repo) {}

    public function getAllByCharacteristic(string $characteristic): array
    {

        return $this->repo->getAllByCharacteristic($characteristic);
    }
}