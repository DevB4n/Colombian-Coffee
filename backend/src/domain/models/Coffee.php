<?php

namespace App\domain\models;



use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Coffee extends Model
{
    protected $table = 'caracteristicas_cafe';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'granos_cafe_id',
        'tiempo_crecimiento_id',
        'region_id',
        'sabor_id',
        'altitud_optima',
        'datos_cafe_id'
    ];

    // Relaciones

    public function grano(): BelongsTo
    {
        return $this->belongsTo(Grain::class, 'granos_cafe_id');
    }

    public function tiempoCrecimiento(): BelongsTo
    {
        return $this->belongsTo(TimeGrowth::class, 'tiempo_crecimiento_id');
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function sabor(): BelongsTo
    {
        return $this->belongsTo(Flavor::class, 'sabor_id');
    }

    public function datosCafe(): BelongsTo
    {
        return $this->belongsTo(CoffeeData::class, 'datos_cafe_id');
    }
}
