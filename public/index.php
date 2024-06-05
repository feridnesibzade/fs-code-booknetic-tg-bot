<?php

error_reporting(E_ALL);

require __DIR__.'/../vendor/autoload.php';

set_error_handler("errorHandler");

set_exception_handler("exceptionHandler");

register_shutdown_function("shutdownHandler");

require __DIR__.'/../src/router/index.php';
require __DIR__.'/../src/router/Api.php';