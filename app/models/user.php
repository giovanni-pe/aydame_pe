<?php
// app/models/User.php (CORREGIDO)

class User extends Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Método de login - CORREGIDO
     */
    public function login($username, $password) {
        $query = "SELECT u.*, up.first_name, up.last_name 
                  FROM users u 
                  LEFT JOIN user_profiles up ON u.id = up.user_id 
                  WHERE u.username = :username OR u.email = :username";
        
        $this->db->prepare($query);
        $this->db->bind(':username', $username);
        $user = $this->db->single();
        
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return false;
    }
    
    /**
     * Registro de usuario
     */
    public function register($data) {
        $query = "INSERT INTO users (username, password, email, role) 
                  VALUES (:username, :password, :email, :role)";
        
        $this->db->prepare($query);
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':role', $data['role'] ?? 'cliente');
        
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }
    
    /**
     * Obtener usuario por ID
     */
    public function getUserById($id) {
        $query = "SELECT u.*, up.* 
                  FROM users u 
                  LEFT JOIN user_profiles up ON u.id = up.user_id 
                  WHERE u.id = :id";
        
        $this->db->prepare($query);
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    /**
     * Actualizar saldo de billetera
     */
    public function updateWallet($userId, $amount, $type, $description, $taskId = null) {
        // Actualizar saldo
        $query = "UPDATE users SET wallet_balance = wallet_balance + :amount WHERE id = :userId";
        $this->db->prepare($query);
        $this->db->bind(':amount', $amount);
        $this->db->bind(':userId', $userId);
        $this->db->execute();
        
        // Registrar transacción
        $query = "INSERT INTO wallet_transactions (user_id, type, amount, description, task_id) 
                  VALUES (:userId, :type, :amount, :description, :taskId)";
        $this->db->prepare($query);
        $this->db->bind(':userId', $userId);
        $this->db->bind(':type', $type);
        $this->db->bind(':amount', $amount);
        $this->db->bind(':description', $description);
        $this->db->bind(':taskId', $taskId);
        
        return $this->db->execute();
    }
    
    /**
     * Verificar si usuario existe por username o email
     */
    public function userExists($username, $email) {
        $query = "SELECT id FROM users WHERE username = :username OR email = :email";
        $this->db->prepare($query);
        $this->db->bind(':username', $username);
        $this->db->bind(':email', $email);
        $result = $this->db->single();
        
        return $result ? true : false;
    }
}