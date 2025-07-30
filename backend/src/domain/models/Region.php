<?php

namespace App\domain\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Region extends Model
{
    protected $table = 'region';
    public $timestamps = false;

    public function pais(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'pais_id');
    }
}
