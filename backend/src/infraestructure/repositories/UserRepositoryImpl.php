<?php

namespace App\infrastructure\repositories;

use App\domain\models\User;
use App\domain\repositories\UserRepository;
use PDO;

class UserRepositoryImpl implements UserRepository {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findByEmail(string $email) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();

        if ($row) {
            return new User($row['id'], $row['email'], $row['password'], $row['nombre_usuario']);
        }

        return null;
    }
}
