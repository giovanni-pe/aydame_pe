<?php
// app/models/WalletTransaction.php

class WalletTransaction extends Model {
    
    public function getUserTransactions($userId, $limit = 20) {
        $query = "SELECT wt.*, t.title as task_title 
                  FROM wallet_transactions wt
                  LEFT JOIN tasks t ON wt.task_id = t.id
                  WHERE wt.user_id = :user_id 
                  ORDER BY wt.created_at DESC 
                  LIMIT :limit";
        
        $this->db->prepare($query);
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':limit', $limit);
        
        return $this->db->resultSet();
    }
    
    public function createTransaction($data) {
        $query = "INSERT INTO wallet_transactions (user_id, type, amount, description, task_id) 
                  VALUES (:user_id, :type, :amount, :description, :task_id)";
        
        $this->db->prepare($query);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':amount', $data['amount']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':task_id', $data['task_id'] ?? null);
        
        return $this->db->execute();
    }
}