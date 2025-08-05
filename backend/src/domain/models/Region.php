<?php

namespace App\domain\models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $table = 'region';
    protected $fillable = ['nombre', 'clima', 'suelo', 'pais_id'];

    public $timestamps = false;

    public function pais()
    {
        return $this->belongsTo(Country::class, 'pais_id');
    }
}
