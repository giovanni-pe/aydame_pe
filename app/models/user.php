<?php
// app/models/User.php

class User extends Model {
    
    private $table = 'users';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get user information by ID
     * 
     * @param int $userId User ID
     * @return object User object
     */
    public function getUserById($userId) {
        $query = "SELECT * FROM {$this->table} WHERE id = :userId";
        
        $this->db->prepare($query);
        $this->db->bind(':userId', $userId);
        
        return $this->db->single();
    }
    
    /**
     * Get user statistics
     * 
     * @param int $userId User ID
     * @return array User statistics
     */
    public function getUserStats($userId = 1) {
        // In a real application, this would fetch from database
        // For demo/prototype purposes, we return hardcoded data
        return [
            [
                'title' => 'Cosas Recibidas',
                'icon' => 'fa-clipboard-list',
                'value' => 28
            ],
            [
                'title' => 'Recomendaciones',
                'icon' => 'fa-comment-alt',
                'value' => 14
            ],
            [
                'title' => 'Estadísticas',
                'icon' => 'fa-chart-bar',
                'value' => 35
            ]
        ];
    }
}