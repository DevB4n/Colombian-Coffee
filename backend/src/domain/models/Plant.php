<?php

namespace App\domain\models;

use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    protected $table = 'plantas_cafe';
    protected $fillable = [
        'nombre_variedad', 'especie', 'nombre_comun',
        'color_hoja', 'tamano_planta_cm', 'descripcion', 'imagen_url'
    ];

    public $timestamps = false;

    public function granos()
    {
        return $this->hasMany(Grain::class, 'planta_id');
    }
}
