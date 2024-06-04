<?php

use App\Controllers\MainController;
use Ferid\Router\Router;


Router::get('/', [MainController::class, 'home']);
