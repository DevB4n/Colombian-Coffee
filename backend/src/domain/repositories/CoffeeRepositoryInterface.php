<?php

namespace App\domain\repositories;

use App\domain\models\Coffee;

interface CoffeeRepositoryInterface {

    public function getAll(): array;
}