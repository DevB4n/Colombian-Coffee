<?php

namespace App\application\usecases;

use App\domain\repositories\CoffeeRepositoryInterface;
use App\domain\models\Coffee;

class CreateCoffeeUseCase
{
    private CoffeeRepositoryInterface $coffeeRepository;

    public function __construct(CoffeeRepositoryInterface $coffeeRepository)
    {
        $this->coffeeRepository = $coffeeRepository;
    }

    public function execute(array $data): array
    {
        return $this->coffeeRepository->create($data);
    }
}
