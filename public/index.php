<?php
// public/index.php (CORREGIDO)

// Load Config
require_once '../app/config/config.php';

// Autoload Core Libraries
spl_autoload_register(function($className) {
    // Try core classes first
    if (file_exists('../system/core/' . $className . '.php')) {
        require_once '../system/core/' . $className . '.php';
        return;
    }
    
    // Try models
    if (file_exists('../app/models/' . $className . '.php')) {
        require_once '../app/models/' . $className . '.php';
        return;
    }
    
    // Try controllers
    if (file_exists('../app/controllers/' . $className . '.php')) {
        require_once '../app/controllers/' . $className . '.php';
        return;
    }
});

// Initialize App
try {
    $app = new App();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}