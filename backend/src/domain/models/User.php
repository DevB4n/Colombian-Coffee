<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{   
    protected $table = 'usuarios';
    protected $primaryKey = 'id'; // Llave primaria
    public $timestamps = false; // Habilita el uso de created_at y updated_at
    protected $fillable = ['nombre', 'email', 'password', 'rol']; // Columnas habilitadas para la insercion
    protected $hidden = ['password'];
}