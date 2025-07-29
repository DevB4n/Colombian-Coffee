<?php

namespace App\domain\validators;


class PropertyValidator{
    private static array $allowedProperties =  ['granos_cafe_id', 'tiempo_crecimiento_id', 'region_id', 'sabor_id', 'altitud_optima', 'datos_cafe_id']; 

    public static function isValid(string $propertie):bool{
        return in_array($propertie,self::$allowedProperties);
    }
}