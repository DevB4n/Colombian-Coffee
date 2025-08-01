<?php

namespace App\domain\models;

use Illuminate\Database\Eloquent\Model;

class Grain extends Model
{
    protected $table = 'granos_cafe';
    protected $fillable = [
        'planta_id', 'tamano_grano_mm', 'color_grano',
        'forma_grano', 'calidad', 'imagen_url'
    ];

    public $timestamps = false;

    public function planta()
    {
        return $this->belongsTo(Plant::class, 'planta_id');
    }
}
