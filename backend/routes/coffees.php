<?php

use App\controllers\CoffeeController;
use Slim\App;

return function(App $app) {
    $app->group('/caracteristicas_cafe', function($group) {
        $group->get('', [CoffeeController::class, 'index']);
        $group->get('/search', [CoffeeController::class, 'getByPropertie']);
        $group->get('/characteristic', [CoffeeController::class, 'getByCharacteristic']);
        $group->post('/post', [CoffeeController::class, 'create']);
        $group->patch('/{table}/{id}', [CoffeeController::class, 'updateFromTable']);
        $group->delete('/delete', [CoffeeController::class, 'deleteFromTable']);
    });
};