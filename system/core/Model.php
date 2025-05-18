<?php
// system/core/Model.php

/**
 * Base Model Class
 * All models will extend this class
 */
class Model {
    protected $db;
    
    public function __construct() {
        $this->db = new Database();
    }
}