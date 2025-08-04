<?php

namespace App\domain\models;

class User {
    public $id;
    public $email;
    public $password;
    public $username;

        // backend/domain/models/User.php
public function __construct($id, $username, $email, $password) {
    $this->id = $id;
    $this->username = $username;
    $this->email = $email;
    $this->password = $password;
}


    public function getPassword() {
        return $this->password;
    }
}
