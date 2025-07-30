<?php

namespace App\domain\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grain extends Model // Permite mapear la db
{
    protected $table = 'granos_cafe';
    public $timestamps = false;

    public function planta(): BelongsTo
    {
        // Ayuda a representar la relacion de tablas
        return $this->belongsTo(Plant::class, 'planta_id');
    }
}
