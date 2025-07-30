<?php

namespace App\useCases;

use App\domain\repositories\CoffeeRepositoryInterface;
use App\domain\validators\PropertyValidator;

/**
 * Caso de uso para obtener variedades de café filtradas por una propiedad específica.
 */
class GetCoffeeByPropertie
{
    public function __construct(private CoffeeRepositoryInterface $repo) {}

    /**
     * Obtiene variedades de café basadas en una propiedad y su valor.
     *
     * @param string $propertie Nombre de la propiedad (ej. 'bean_size', 'optimal_altitude').
     * @param mixed $value Valor para filtrar.
     * @return array Lista de variedades de café que coinciden con el criterio.
     * @throws InvalidArgumentException Si la propiedad no es válida.
     */
    public function execute(string $propertie, mixed $value): array
    {
        PropertyValidator::validate($propertie);
        return $this->repo->getByPropertie($propertie, $value);
    }
}