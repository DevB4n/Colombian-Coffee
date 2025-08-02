<?php

namespace App\useCases;

use App\domain\repositories\UserRepository;

class LoginUser {
    private $userRepo;

    public function __construct(UserRepository $userRepo) {
        $this->userRepo = $userRepo;
    }

    public function execute($email, $password) {
        $user = $this->userRepo->findByEmail($email);

        if (!$user || !password_verify($password, $user->password)) {
            return null;
        }

        return [
            "id" => $user->id,
            "nombre_usuario" => $user->nombre_usuario,
            "email" => $user->email
        ];
    }
}
