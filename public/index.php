<?php
session_start();
require_once '../app/config/config.php';

// Autoload Core Libraries
spl_autoload_register(function($className){
    $file = '../app/core/' . $className . '.php';
    if(file_exists($file)){
        require_once $file;
    }
});

// Load helpers
require_once '../app/helpers/auth_helper.php';

// Init Core Library
$init = new App();
