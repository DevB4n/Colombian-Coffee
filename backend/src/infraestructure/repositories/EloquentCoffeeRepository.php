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
        
    }
}