<?php

namespace App\infraestructure\repositories;

use App\domain\models\Coffee;
use App\domain\repositories\CoffeeRepositoryInterface;

class EloquentCoffeeRepository implements CoffeeRepositoryInterface
{
    public function getAll(): array
    {
        $coffees = Coffee::with(['grano.planta', 'tiempoCrecimiento', 'region.pais', 'sabor', 'datosCafe'])->get();
    
        $result = [];
    
        foreach ($coffees as $coffee) {
            // Limpio los ids de grano
            $grano = $coffee->grano->toArray();
            unset($grano['id'], $grano['planta_id']);
    
            // Limpio los ids de planta dentro de grano
            if (isset($grano['planta'])) {
                unset($grano['planta']['id']);
            }
    
            // Limpio los ids de tiempo_crecimiento
            $tiempoCrecimiento = $coffee->tiempoCrecimiento->toArray();
            unset($tiempoCrecimiento['id']);
    
            // Limpio ids de datos_cafe
            $datosCafe = $coffee->datosCafe->toArray();
            unset($datosCafe['id']);
    
            // No necesitas limpiar region y sabor si sólo usas nombre/característica
            $result[] = [
                'id' => $coffee->id,  // solo el id principal, no en anidados
                'grano' => $grano,
                'tiempo_crecimiento' => $tiempoCrecimiento,
                'region' => $coffee->region->nombre,
                'sabor' => $coffee->sabor->caracteristica,
                'altitud_optima' => $coffee->altitud_optima,
                'datos_cafe' => $datosCafe,
            ];
        }
    
        return $result;
    }
    


    public function getByPropertie(string $propertie, mixed $value): array
    {
        $coffees = Coffee::with([
            'grano.planta',
            'tiempoCrecimiento',
            'region.pais',
            'sabor',
            'datosCafe'
        ])->where($propertie, $value)->get();

        $result = [];

        foreach ($coffees as $coffee) {
            // Limpio los ids de grano
            $grano = $coffee->grano->toArray();
            unset($grano['id'], $grano['planta_id']);

            // Limpio los ids de planta dentro de grano
            if (isset($grano['planta'])) {
                unset($grano['planta']['id']);
            }

            // Limpio los ids de tiempo_crecimiento
            $tiempoCrecimiento = $coffee->tiempoCrecimiento->toArray();
            unset($tiempoCrecimiento['id']);

            // Limpio ids de datos_cafe
            $datosCafe = $coffee->datosCafe->toArray();
            unset($datosCafe['id']);

            $result[] = [
                'id' => $coffee->id,
                'grano' => $grano,
                'tiempo_crecimiento' => $tiempoCrecimiento,
                'region' => $coffee->region->nombre,
                'sabor' => $coffee->sabor->caracteristica,
                'altitud_optima' => $coffee->altitud_optima,
                'datos_cafe' => $datosCafe,
            ];
        }

        return $result;
    }


    public function getAllByCharacteristic(string $characteristic): array
{
    $coffees = Coffee::with([
        'grano.planta',
        'tiempoCrecimiento',
        'region.pais',
        'sabor',
        'datosCafe'
    ])->get();

    $result = [];

    foreach ($coffees as $coffee) {
        $nombreVariedad = $coffee->grano->planta->nombre_variedad ?? null;

        $value = match ($characteristic) {
            // 🟫 PLANTA
            'nombre_variedad' => $coffee->grano->planta->nombre_variedad ?? null,
            'especie' => $coffee->grano->planta->especie ?? null,
            'nombre_comun' => $coffee->grano->planta->nombre_comun ?? null,
            'color_hoja' => $coffee->grano->planta->color_hoja ?? null,
            'tamano_planta_cm' => $coffee->grano->planta->tamano_planta_cm ?? null,
            'descripcion' => $coffee->grano->planta->descripcion ?? null,
            'porte' => match (true) {
                $coffee->grano->planta->tamano_planta_cm > 450 => 'Alto',
                $coffee->grano->planta->tamano_planta_cm >= 350 => 'Medio Largo',
                $coffee->grano->planta->tamano_planta_cm >= 200 => 'Medio Corto',
                $coffee->grano->planta->tamano_planta_cm >= 100 => 'Medio',
                $coffee->grano->planta->tamano_planta_cm > 0 => 'Bajo',
                default => null,
            },

            // 🟤 GRANO
            'tamano_grano_mm' => $coffee->grano->tamano_grano_mm ?? null,
            'color_grano' => $coffee->grano->color_grano ?? null,
            'forma_grano' => $coffee->grano->forma_grano ?? null,
            'calidad' => $coffee->grano->calidad ?? null,
            'tamano_grano' => match (true) {
                $coffee->grano->tamano_grano_mm < 6.0 => 'Pequeño',
                $coffee->grano->tamano_grano_mm >= 6.75 => 'Grande',
                default => 'Mediano',
            },

            // 🟠 TIEMPO CRECIMIENTO
            'desde_anhos' => $coffee->tiempoCrecimiento->Desde_anhos ?? null,
            'hasta_anhos' => $coffee->tiempoCrecimiento->Hasta_anhos ?? null,

            // 🟡 REGIÓN
            'region' => $coffee->region->nombre ?? null,
            'clima' => $coffee->region->clima ?? null,
            'suelo' => $coffee->region->suelo ?? null,

            // 🟢 PAÍS
            'pais' => $coffee->region->pais->nombre ?? null,

            // 🔵 SABOR
            'sabor' => $coffee->sabor->caracteristica ?? null,

            // 🟣 DATOS CAFÉ
            'altitud_optima' => $coffee->altitud_optima ?? null,
            'requerimiento_nutricion' => $coffee->datosCafe->requerimiento_nutricion ?? null,
            'densidad_plantacion' => $coffee->datosCafe->densidad_plantacion ?? null,
            'resistencia' => $coffee->datosCafe->resistencia ?? null,
            'primera_siembra' => $coffee->datosCafe->primera_siembra ?? null,

            default => null,
        };

        if ($value !== null && $nombreVariedad !== null) {
            $result[] = [
                'nombre_variedad' => $nombreVariedad,
                $characteristic => $value,
            ];
        }
    }

    return $result;
}


}