<?php

use App\Controllers\UserController;
use Slim\App;

return function (App $app) {
    $app->group('/register', function ($group) {
        $group->post('/user', [UserController::class, 'createUser']);
        $group->post('/admin', [UserController::class, 'createAdmin']);
    });
};