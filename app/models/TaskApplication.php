<?php
// app/models/TaskApplication.php

class TaskApplication extends Model {
    
    public function apply($data) {
        $query = "INSERT INTO task_applications (task_id, worker_id, proposal_amount, proposal_message) 
                  VALUES (:task_id, :worker_id, :proposal_amount, :proposal_message)";
        
        $this->db->prepare($query);
        $this->db->bind(':task_id', $data['task_id']);
        $this->db->bind(':worker_id', $data['worker_id']);
        $this->db->bind(':proposal_amount', $data['proposal_amount']);
        $this->db->bind(':proposal_message', $data['proposal_message'] ?? '');
        
        return $this->db->execute();
    }
    
    public function getTaskApplications($taskId) {
        $query = "SELECT ta.*, up.first_name, up.last_name, up.average_rating, up.total_jobs, up.description
                  FROM task_applications ta
                  JOIN user_profiles up ON ta.worker_id = up.user_id
                  WHERE ta.task_id = :task_id AND ta.status = 'pendiente'
                  ORDER BY ta.created_at DESC";
        
        $this->db->prepare($query);
        $this->db->bind(':task_id', $taskId);
        return $this->db->resultSet();
    }
}
