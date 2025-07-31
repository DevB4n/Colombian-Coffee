<?php

#patron usado: singleton, se uso al momento de crear una nueva instancia(conexion a la base de datos);

namespace App\infraestructure\database;

use Exception;
use Illuminate\Database\Capsule\Manager as Manager;


class Connection
{
    private static ?Connection $instance = null;

    private function __construct()
    {
        $capsule = new Manager;

        $capsule->addConnection([
            'driver' => 'mysql',
            'host' => $_ENV['DB_HOST'],
            'port' => $_ENV['DB_PORT'],
            'database' => $_ENV['DB_NAME'],
            'username' => $_ENV['DB_USERNAME'],
            'password' => $_ENV['DB_PASSWORD'],
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => ''
        ]);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }

    public static function init(): string|bool
    {
        if (!self::$instance) {
            try {
                self::$instance = new self();
                Manager::connection()->getPdo();
                return true;
            } catch (Exception $e) {
                return "la conexion con la base de datos ha fallado: " . $e->getMessage();
            }
        }
        return true;
    }
}
