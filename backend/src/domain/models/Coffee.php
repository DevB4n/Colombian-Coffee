<?php

namespace App\domain\models;

use Illuminate\Database\Eloquent\Model;

class Coffee extends Model
{
    protected $table = 'caracteristicas_cafe'; // Nombre tabla
    protected $primaryKey = 'id'; // Llave primaria
    public $timestamps = false; // Niega columnas de modificacion por fecha
    protected $fillable = ['granos_cafe_id', 'tiempo_crecimiento_id', 'region_id', 'sabor_id', 'altitud_optima', 'datos_cafe_id']; // Columnas a llenar
}