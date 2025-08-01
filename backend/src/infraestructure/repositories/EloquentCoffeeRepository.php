<?php

namespace App\infraestructure\repositories;

use App\domain\models\Coffee;
use App\domain\models\Flavor;
use App\domain\models\Grain;
use App\domain\models\Plant;
use App\domain\models\TimeGrowth;
use App\domain\models\Region;
use App\domain\models\CoffeeData;
use App\domain\repositories\CoffeeRepositoryInterface;
use Illuminate\Database\Capsule\Manager as Capsule;

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
                    ($coffee->grano->planta->tamano_planta_cm ?? 0) > 450 => 'Alto',
                    ($coffee->grano->planta->tamano_planta_cm ?? 0) >= 350 => 'Medio Largo',
                    ($coffee->grano->planta->tamano_planta_cm ?? 0) >= 200 => 'Medio Corto',
                    ($coffee->grano->planta->tamano_planta_cm ?? 0) >= 100 => 'Medio',
                    ($coffee->grano->planta->tamano_planta_cm ?? 0) > 0 => 'Bajo',
                    default => null
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

                // 🟡 REGION
                'region' => $coffee->region->nombre ?? null,
                'clima' => $coffee->region->clima ?? null,
                'suelo' => $coffee->region->suelo ?? null,

                // 🟢 PAIS
                'pais' => $coffee->region->pais->nombre ?? null,

                // 🔵 SABOR
                'sabor' => $coffee->sabor->caracteristica ?? null,

                // 🟣 DATOS CAFE
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

    public function deleteFromTableById(string $table, int $id): int
    {
        // Tabla segura con su modelo asociado
        $allowedTables = [
            'sabor' => Flavor::class,
            'region' => Region::class,
            'planta' => Plant::class,
            'grano' => Grain::class,
            'tiempo_crecimiento' => TimeGrowth::class,
            'datos_cafe' => CoffeeData::class,
            // Agrega mas si las necesitas
        ];

        if (!array_key_exists($table, $allowedTables)) {
            throw new \InvalidArgumentException("Tabla '$table' no permitida para eliminación.");
        }

        $model = $allowedTables[$table];

        // Encuentra y elimina
        $record = $model::find($id);
        if (!$record) {
            return 0;
        }

        $record->delete();
        return 1;
    }

    public function create(array $data): array
    {
        return Capsule::connection()->transaction(function () use ($data) {
            // 1. Crear o encontrar planta
            $plant = Plant::firstOrCreate(
                ['nombre_variedad' => $data['plant']['nombre_variedad']],
                $data['plant']
            );

            // 2. Crear grano
            $grain = new Grain($data['grain']);
            $grain->planta_id = $plant->id;
            $grain->save();

            // 3. Tiempo crecimiento
            $growth = TimeGrowth::create($data['time_growth']);

            // 4. Sabor
            $flavor = Flavor::firstOrCreate(
                ['caracteristica' => $data['flavor']]
            );

            // 5. Región
            $region = Region::firstOrCreate(
                ['nombre' => $data['region']['nombre']],
                $data['region']
            );

            // 6. Datos de cafe
            $coffeeData = CoffeeData::create($data['coffee_data']);

            // 7. Crear cafe principal
            $coffee = new Coffee([
                'granos_cafe_id' => $grain->id,
                'tiempo_crecimiento_id' => $growth->id,
                'region_id' => $region->id,
                'sabor_id' => $flavor->id,
                'altitud_optima' => $data['altitud_optima'],
                'datos_cafe_id' => $coffeeData->id,
            ]);

            $coffee->save();

            return $coffee->load([
                'grano.planta',
                'tiempoCrecimiento',
                'region.pais',
                'sabor',
                'datosCafe'
            ])->toArray();
        });
    }

    public function updateFromTableById(string $table, int $id, array $data): array
    {
        // Lista de tablas permitidas con su modelo asociado
        $allowedTables = [
            'planta' => Plant::class,
            'grano' => Grain::class,
            'tiempo_crecimiento' => TimeGrowth::class,
            'region' => Region::class,
            'sabor' => Flavor::class,
            'datos_cafe' => CoffeeData::class,
            'cafe' => Coffee::class,
        ];

        if (!array_key_exists($table, $allowedTables)) {
            throw new \InvalidArgumentException("Tabla '$table' no permitida para actualización.");
        }

        $model = $allowedTables[$table];

        // Buscar el registro
        $record = $model::find($id);
        if (!$record) {
            throw new \Exception("No se encontró el registro en '$table' con ID $id.");
        }

        // Actualizar los campos
        $record->update($data);

        // Devolver el registro actualizado
        return $record->fresh()->toArray();
    }

}