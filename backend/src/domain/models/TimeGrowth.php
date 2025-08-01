<?php
namespace App\domain\models;

use Illuminate\Database\Eloquent\Model;

class TimeGrowth extends Model
{
    protected $table = 'tiempo_crecimiento';
    protected $fillable = ['Desde_anhos', 'Hasta_anhos'];

    public $timestamps = false;
}
