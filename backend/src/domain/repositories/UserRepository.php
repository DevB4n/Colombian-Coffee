<?php

namespace App\domain\repositories;

use App\domain\models\User;

interface UserRepository {
    public function findByEmail(string $email);
    public function create(string $email, string $username, string $password): User;
}
