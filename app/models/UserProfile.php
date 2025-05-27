
<?php
// app/models/UserProfile.php

class UserProfile extends Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Crear perfil de usuario
     */
    public function createProfile($data) {
        $query = "INSERT INTO user_profiles (user_id, first_name, last_name, description, dni, phone, city, birth_date, optional_phone) 
                  VALUES (:user_id, :first_name, :last_name, :description, :dni, :phone, :city, :birth_date, :optional_phone)";
        
        $this->db->prepare($query);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':last_name', $data['last_name']);
        $this->db->bind(':description', $data['description'] ?? '');
        $this->db->bind(':dni', $data['dni']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':city', $data['city']);
        $this->db->bind(':birth_date', $data['birth_date']);
        $this->db->bind(':optional_phone', $data['optional_phone'] ?? null);
        
        if ($this->db->execute()) {
            // Marcar perfil como completo en la tabla users
            $query = "UPDATE users SET is_profile_complete = TRUE WHERE id = :user_id";
            $this->db->prepare($query);
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->execute();
            return true;
        }
        return false;
    }
    
    /**
     * Actualizar perfil de usuario
     */
    public function updateProfile($data) {
        $query = "UPDATE user_profiles SET 
                  first_name = :first_name, 
                  last_name = :last_name, 
                  description = :description, 
                  dni = :dni, 
                  phone = :phone, 
                  city = :city, 
                  birth_date = :birth_date, 
                  optional_phone = :optional_phone 
                  WHERE user_id = :user_id";
        
        $this->db->prepare($query);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':last_name', $data['last_name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':dni', $data['dni']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':city', $data['city']);
        $this->db->bind(':birth_date', $data['birth_date']);
        $this->db->bind(':optional_phone', $data['optional_phone']);
        
        return $this->db->execute();
    }
    
    /**
     * Obtener perfil de usuario
     */
    public function getProfile($userId) {
        $query = "SELECT * FROM user_profiles WHERE user_id = :user_id";
        $this->db->prepare($query);
        $this->db->bind(':user_id', $userId);
        return $this->db->single();
    }
    
    /**
     * Actualizar calificación promedio del usuario
     */
    public function updateRating($userId) {
        $query = "UPDATE user_profiles SET 
                  average_rating = (SELECT AVG(rating) FROM ratings WHERE worker_id = :user_id),
                  total_jobs = (SELECT COUNT(*) FROM tasks WHERE selected_worker_id = :user_id AND status = 'completada')
                  WHERE user_id = :user_id";
        $this->db->prepare($query);
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }
    
    /**
     * Verificar si el usuario tiene perfil
     */
    public function hasProfile($userId) {
        $query = "SELECT COUNT(*) as count FROM user_profiles WHERE user_id = :user_id";
        $this->db->prepare($query);
        $this->db->bind(':user_id', $userId);
        $result = $this->db->single();
        
        return $result->count > 0;
    }
}