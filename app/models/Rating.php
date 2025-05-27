<?php 
class Rating extends Model {
    
    public function rateWorker($data) {
        $query = "INSERT INTO ratings (task_id, client_id, worker_id, rating, comment) 
                  VALUES (:task_id, :client_id, :worker_id, :rating, :comment)";
        
        $this->db->prepare($query);
        $this->db->bind(':task_id', $data['task_id']);
        $this->db->bind(':client_id', $data['client_id']);
        $this->db->bind(':worker_id', $data['worker_id']);
        $this->db->bind(':rating', $data['rating']);
        $this->db->bind(':comment', $data['comment'] ?? '');
        
        if ($this->db->execute()) {
            // Actualizar promedio de calificación del trabajador
            $profileModel = new UserProfile();
            $profileModel->updateRating($data['worker_id']);
            return true;
        }
        return false;
    }
    
    public function getWorkerRatings($workerId, $limit = 10) {
        $query = "SELECT r.*, up.first_name, up.last_name, t.title
                  FROM ratings r
                  JOIN user_profiles up ON r.client_id = up.user_id
                  JOIN tasks t ON r.task_id = t.id
                  WHERE r.worker_id = :worker_id
                  ORDER BY r.created_at DESC
                  LIMIT :limit";
        
        $this->db->prepare($query);
        $this->db->bind(':worker_id', $workerId);
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }
}