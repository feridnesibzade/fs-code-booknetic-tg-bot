<?php

function config($fileName){
    $configPath = realpath($_SERVER['DOCUMENT_ROOT'])."/../src/config/$fileName.php";
    if(file_exists($configPath)){
        return include $configPath;
    }
}

