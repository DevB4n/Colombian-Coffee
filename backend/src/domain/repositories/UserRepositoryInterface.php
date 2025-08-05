<?php

namespace App\Domain\Repositories;

use App\Domain\Models\User;
use App\DTOs\UserDTO;

interface UserRepositoryInterface
{
    public function create(UserDTO $dto): User;
}