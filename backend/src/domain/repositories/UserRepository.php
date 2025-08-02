<?php

namespace App\domain\repositories;

interface UserRepository {
    public function findByEmail(string $email);
}
