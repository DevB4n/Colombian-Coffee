<?php

namespace App\domain\models;

class User {
    public $id;
    public $email;
    public $password;
    public $nombre_usuario;

    public function __construct($id, $email, $password, $nombre_usuario) {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->nombre_usuario = $nombre_usuario;
    }
}
