
<?php
require_once '../app/config/config.php';

// Autoload Core Libraries
spl_autoload_register(function($className) {
    if (file_exists('../system/core/' . $className . '.php')) {
        require_once '../system/core/' . $className . '.php';
    }
});

// Initialize App
$app = new App();