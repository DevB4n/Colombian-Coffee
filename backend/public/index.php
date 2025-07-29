<?php

use Dotenv\Dotenv;
use App\infraestructure\database\Connection;


require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$connection = Connection::init();

if ($connection !== true){
    die($connection);
}
