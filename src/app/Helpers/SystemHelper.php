<?php

function errorHandler($errNo, $errStr, $errFile, $errLine) {
    $message = "[ ERROR ] : ($errNo) $errStr - $errFile:$errLine";
    error_log($message . PHP_EOL, 3, $_SERVER['DOCUMENT_ROOT'].'/../src/storage/error.log');
}

// Define the custom exception handler function
function exceptionHandler($exception) {
    $message = "[ EXCEPTION ] : " . $exception->getMessage() . " - " . $exception->getFile() . ":" . $exception->getLine();
    error_log($message . PHP_EOL, 3, $_SERVER['DOCUMENT_ROOT'].'/../src/storage/error.log');
}

// Define the custom shutdown function to catch fatal errors
function shutdownHandler() {
    $error = error_get_last();
    if ($error !== NULL) {
        $message = "[ FATAL ERROR ] : (" . $error['type'] . ") " . $error['message'] . " - " . $error['file'] . ":" . $error['line'];
        error_log($message . PHP_EOL, 3, $_SERVER['DOCUMENT_ROOT'].'/../src/storage/error.log');
    }
}


