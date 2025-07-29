<?php

use App\controllers\CoffeeController;
use Slim\App;

return function(App $app) {
    $app->group('/caracteristicas_cafe', function($group) {
        $group->get('', [CoffeeController::class, 'index']);
        $group->get('/search', [CoffeeController::class, 'getByPropertie']);
    });
};