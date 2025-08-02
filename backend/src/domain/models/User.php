<?php

namespace App\domain\models;

class User {
    public $id;
    public $email;
    public $password;
    public $username;

    public function __construct($id, $email, $password, $username) {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->username = $username;
    }

    public function getPassword() {
        return $this->password;
    }
}
