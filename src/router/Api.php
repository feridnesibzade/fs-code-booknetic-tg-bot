<?php
use App\Controllers\TelegramBotController;
use Ferid\Router\Router;

Router::get("/api", [TelegramBotController::class, "handleRequest"]);

Router::get("/api/test", [TelegramBotController::class, "index"]);