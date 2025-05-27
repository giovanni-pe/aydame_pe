<?php
// system/core/App.php (corregido)

class App {
    protected $controller = 'Home';
    protected $method = 'index';
    protected $params = [];
    
    public function __construct() {
        $url = $this->parseUrl();
        
        // Manejar rutas especiales
        if (empty($url[0])) {
            $this->controller = 'Home';
            $this->method = 'index';
        } elseif ($url[0] === 'dashboard') {
            $this->controller = 'Dashboard';
            $this->method = 'index';
            unset($url[0]);
        } else {
            // Check if controller exists
            if (file_exists('../app/controllers/' . ucfirst($url[0]) . 'Controller.php')) {
                $this->controller = ucfirst($url[0]);
                unset($url[0]);
            }
        }
        
        // Include the controller
        require_once '../app/controllers/' . $this->controller . 'Controller.php';
        
        // Instantiate controller
        $this->controller = $this->controller . 'Controller';
        $this->controller = new $this->controller();
        
        // Check for method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }
        
        // Get params
        $this->params = $url ? array_values($url) : [];
        
        // Call a callback with array of params
        call_user_func_array([$this->controller, $this->method], $this->params);
    }
    
    protected function parseUrl() {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }
        
        return [];
    }
}
