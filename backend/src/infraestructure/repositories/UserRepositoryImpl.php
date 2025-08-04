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
            return new User(
                $row['id'],
                $row['username'], // username
                $row['email'],
                $row['password']
            );
        }

        return null;
    }

    public function create(string $email, string $username, string $password): User {
    $stmt = $this->pdo->prepare("INSERT INTO usuarios (email, username, password) VALUES (:email, :username, :password)");
    $stmt->execute([
        'email' => $email,
        'username' => $username,
        'password' => $password
    ]);

    $id = $this->pdo->lastInsertId();

    return new User($id, $username, $email, $password);
}

}
