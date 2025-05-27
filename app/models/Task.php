<?php
// app/models/Task.php

class Task extends Model {
    
    public function create($data) {
        $query = "INSERT INTO tasks (client_id, category_id, title, description, budget, location) 
                  VALUES (:client_id, :category_id, :title, :description, :budget, :location)";
        
        $this->db->prepare($query);
        $this->db->bind(':client_id', $data['client_id']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':budget', $data['budget']);
        $this->db->bind(':location', $data['location']);
        
        return $this->db->execute() ? $this->db->lastInsertId() : false;
    }
    
    public function getAvailableTasks() {
        $query = "SELECT t.*, tc.name as category_name, tc.icon, 
                         up.first_name, up.last_name
                  FROM tasks t 
                  JOIN task_categories tc ON t.category_id = tc.id
                  JOIN user_profiles up ON t.client_id = up.user_id
                  WHERE t.status = 'publicada' 
                  ORDER BY t.created_at DESC";
        $this->db->prepare($query);
        return $this->db->resultSet();
    }
    
    public function getTaskById($id) {
        $query = "SELECT t.*, tc.name as category_name, tc.icon,
                         up.first_name, up.last_name, up.phone
                  FROM tasks t 
                  JOIN task_categories tc ON t.category_id = tc.id
                  JOIN user_profiles up ON t.client_id = up.user_id
                  WHERE t.id = :id";
        $this->db->prepare($query);
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    public function getUserTasks($userId, $userRole) {
        if ($userRole === 'cliente') {
            $query = "SELECT t.*, tc.name as category_name, tc.icon,
                             wp.first_name as worker_name, wp.last_name as worker_lastname
                      FROM tasks t 
                      JOIN task_categories tc ON t.category_id = tc.id
                      LEFT JOIN user_profiles wp ON t.selected_worker_id = wp.user_id
                      WHERE t.client_id = :user_id 
                      ORDER BY t.created_at DESC";
        } else {
            $query = "SELECT t.*, tc.name as category_name, tc.icon,
                             up.first_name as client_name, up.last_name as client_lastname
                      FROM tasks t 
                      JOIN task_categories tc ON t.category_id = tc.id
                      JOIN user_profiles up ON t.client_id = up.user_id
                      WHERE t.selected_worker_id = :user_id 
                      ORDER BY t.created_at DESC";
        }
        
        $this->db->prepare($query);
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }
    
    public function searchTasks($search, $userId) {
        $query = "SELECT t.*, tc.name as category_name, tc.icon,
                         up.first_name, up.last_name
                  FROM tasks t 
                  JOIN task_categories tc ON t.category_id = tc.id
                  JOIN user_profiles up ON t.client_id = up.user_id
                  WHERE (t.title LIKE :search OR t.description LIKE :search OR tc.name LIKE :search)
                  AND t.status = 'publicada'
                  AND t.client_id != :user_id
                  ORDER BY t.created_at DESC";
        
        $this->db->prepare($query);
        $this->db->bind(':search', '%' . $search . '%');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }
    
    public function selectWorker($taskId, $workerId, $applicationId) {
        // Actualizar tarea
        $query = "UPDATE tasks SET selected_worker_id = :worker_id, status = 'asignada' WHERE id = :task_id";
        $this->db->prepare($query);
        $this->db->bind(':worker_id', $workerId);
        $this->db->bind(':task_id', $taskId);
        $this->db->execute();
        
        // Marcar aplicación como aceptada
        $query = "UPDATE task_applications SET status = 'aceptada' WHERE id = :app_id";
        $this->db->prepare($query);
        $this->db->bind(':app_id', $applicationId);
        $this->db->execute();
        
        // Rechazar otras aplicaciones
        $query = "UPDATE task_applications SET status = 'rechazada' WHERE task_id = :task_id AND id != :app_id";
        $this->db->prepare($query);
        $this->db->bind(':task_id', $taskId);
        $this->db->bind(':app_id', $applicationId);
        return $this->db->execute();
    }
    
    public function updateStatus($taskId, $status) {
        $query = "UPDATE tasks SET status = :status WHERE id = :task_id";
        $this->db->prepare($query);
        $this->db->bind(':status', $status);
        $this->db->bind(':task_id', $taskId);
        return $this->db->execute();
    }
    
    public function getCategories() {
        $query = "SELECT * FROM task_categories ORDER BY name";
        $this->db->prepare($query);
        return $this->db->resultSet();
    }
}