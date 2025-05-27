<?php 
class TaskEvidence extends Model {
    
    public function submitEvidence($data) {
        $query = "INSERT INTO task_evidences (task_id, worker_id, description, image_path) 
                  VALUES (:task_id, :worker_id, :description, :image_path)";
        
        $this->db->prepare($query);
        $this->db->bind(':task_id', $data['task_id']);
        $this->db->bind(':worker_id', $data['worker_id']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':image_path', $data['image_path']);
        
        if ($this->db->execute()) {
            // Actualizar estado de la tarea
            $query = "UPDATE tasks SET status = 'esperando_evidencia' WHERE id = :task_id";
            $this->db->prepare($query);
            $this->db->bind(':task_id', $data['task_id']);
            $this->db->execute();
            return true;
        }
        return false;
    }
    
    public function getTaskEvidence($taskId) {
        $query = "SELECT * FROM task_evidences WHERE task_id = :task_id ORDER BY created_at DESC LIMIT 1";
        $this->db->prepare($query);
        $this->db->bind(':task_id', $taskId);
        return $this->db->single();
    }
    
    public function approveEvidence($evidenceId, $taskId) {
        $query = "UPDATE task_evidences SET status = 'aprobada' WHERE id = :evidence_id";
        $this->db->prepare($query);
        $this->db->bind(':evidence_id', $evidenceId);
        $this->db->execute();
        
        // Actualizar estado de la tarea
        $query = "UPDATE tasks SET status = 'completada' WHERE id = :task_id";
        $this->db->prepare($query);
        $this->db->bind(':task_id', $taskId);
        return $this->db->execute();
    }
    
    public function rejectEvidence($evidenceId, $feedback) {
        $query = "UPDATE task_evidences SET status = 'rechazada', feedback = :feedback WHERE id = :evidence_id";
        $this->db->prepare($query);
        $this->db->bind(':evidence_id', $evidenceId);
        $this->db->bind(':feedback', $feedback);
        return $this->db->execute();
    }
}
