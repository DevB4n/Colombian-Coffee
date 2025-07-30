<?php

namespace App\domain\validators;

use InvalidArgumentException;

/**
 * Valida propiedades permitidas para filtrar variedades de café.
 */
class PropertyValidator
{
    private static array $allowedProperties = [
        'bean_size', // Tamaño del grano
        'growth_time_id', // Tiempo de crecimiento
        'region_id', // Región o departamento
        'flavor_profile_id', // Perfil de sabor
        'optimal_altitude', // Altitud óptima
        'coffee_data_id' // Datos agronómicos del café
    ];

    /**
     * Verifica si una propiedad es válida para filtrar variedades de café.
     *
     * @param string $propertie Nombre de la propiedad a validar.
     * @return void
     * @throws InvalidArgumentException Si la propiedad no está en la lista de permitidas.
     */
    public static function validate(string $propertie): void
    {
        if (!in_array($propertie, self::$allowedProperties)) {
            throw new InvalidArgumentException("La propiedad '$propertie' no es válida. Propiedades permitidas: " . implode(', ', self::$allowedProperties));
        }
    }

    /**
     * Obtiene la lista de propiedades permitidas.
     *
     * @return array Lista de propiedades válidas.
     */
    public static function getAllowedProperties(): array
    {
        return self::$allowedProperties;
    }
}