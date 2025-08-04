<?php

namespace App\useCases;

use App\domain\models\User;
use App\domain\repositories\UserRepository;

class RegisterUser {
    private $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function execute(string $email, string $username, string $password): User {
        $existingUser = $this->userRepository->findByEmail($email);
        if ($existingUser) {
            throw new \Exception("El correo ya está registrado");
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        return $this->userRepository->create($email, $username, $hashedPassword);
    }
}
