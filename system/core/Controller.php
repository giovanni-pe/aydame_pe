<?php
// system/core/Controller.php

/**
 * Base Controller
 * Loads models and views
 */
class Controller {
    
    /**
     * Load model
     * 
     * @param string $model Model name
     * @return object Model instance
     */
    public function model($model) {
        // Require model file
        require_once '../app/models/' . $model . '.php';
        
        // Instantiate model
        return new $model();
    }
    
    /**
     * Load view
     * 
     * @param string $view View name
     * @param array $data Data to pass to the view
     * @return void
     */
    public function view($view, $data = []) {
        // Check if view exists
        if (file_exists('../app/views/' . $view . '.php')) {
            // Extract data to make it available in the view
            extract($data);
            
            // Start output buffering
            ob_start();
            
            // Include view file
            include '../app/views/' . $view . '.php';
            
            // Get buffered content
            $content = ob_get_clean();
            
            // Include layout with content
            include '../app/views/layouts/default.php';
        } else {
            // View not found
            die('View does not exist');
        }
    }
}