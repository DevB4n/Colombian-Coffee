<?php

namespace App\domain\models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'pais';
    protected $fillable = ['nombre'];

    public $timestamps = false;

    public function regiones()
    {
        return $this->hasMany(Region::class);
    }
}
