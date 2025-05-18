<?php
// app/models/Task.php

class Task extends Model {
    
    private $table = 'tasks';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get recent tasks for a user
     * 
     * @param int $userId User ID
     * @param int $limit Number of tasks to retrieve
     * @return array Array of tasks
     */
    public function getRecentTasks($userId = 1, $limit = 5) {
        $query = "SELECT t.*, tt.icon_class, tt.color
                  FROM {$this->table} t
                  JOIN task_types tt ON t.type_id = tt.id
                  WHERE t.user_id = :userId
                  ORDER BY t.created_at DESC
                  LIMIT :limit";
        
        $this->db->prepare($query);
        $this->db->bind(':userId', $userId);
        $this->db->bind(':limit', $limit);
        
        return $this->db->resultSet();
    }
    
    /**
     * For demo/prototype purposes, return hardcoded tasks
     */
    public function getRecentTasksDemo() {
        return [
            [
                'id' => 1,
                'title' => 'Arreglar el jardín',
                'time' => '1h',
                'icon' => 'fa-seedling',
                'color' => 'primary'
            ],
            [
                'id' => 2,
                'title' => 'Pasear al perro',
                'time' => '3d',
                'icon' => 'fa-dog',
                'color' => 'info'
            ]
        ];
    }
}