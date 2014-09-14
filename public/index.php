<?php

chdir(dirname(__DIR__));

// Setup autoloading
include 'init_autoloader.php';

define('BASE_DIR', dirname(__DIR__));

// Run the application!
Zend\Mvc\Application::init(include 'config/application.config.php')->run()->send();
