<?php

namespace App\useCases;

use App\domain\repositories\UserRepository;
use App\domain\models\User;

class LoginUser {
    private $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function execute(string $email, string $password): User {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            throw new \Exception("Usuario no encontrado");
        }

        if (!password_verify($password, $user->getPassword())) {
            throw new \Exception("Contraseña incorrecta");
        }

        return $user;
    }
}
