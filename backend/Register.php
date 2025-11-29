<?php

require_once 'Database.php';

class Register {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function registerUser($name, $email, $password) {
        // Validate inputs
        if (empty($name) || empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'All fields are required'];
        }
        
        // Check if name already exists
        $existingUser = $this->db->fetchOne(
            "SELECT * FROM users WHERE name = ?", 
            [$name]
        );
        
        if ($existingUser) {
            return ['success' => false, 'message' => 'user already exists'];
        }
        
        // Check if email already exists
        $existingEmail = $this->db->fetchOne(
            "SELECT * FROM users WHERE email = ?", 
            [$email]
        );
        
        if ($existingEmail) {
            return ['success' => false, 'message' => 'Email already exists'];
        }
        
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            $this->db->execute(
                "INSERT INTO users (name, email, password) VALUES (?, ?, ?)",
                [$name, $email, $hashedPassword]
            );
            
            return ['success' => true, 'message' => 'Registration successful'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Registration failed: ' . $e->getMessage()];
        }
    }
}