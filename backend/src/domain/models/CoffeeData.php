<?php
namespace App\domain\models;

use Illuminate\Database\Eloquent\Model;

class CoffeeData extends Model
{
    protected $table = 'datos_cafe';
    protected $fillable = [
        'requerimiento_nutricion', 'densidad_plantacion',
        'resistencia', 'primera_siembra'
    ];

    public $timestamps = false;
}
