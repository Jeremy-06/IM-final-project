<?php

require_once __DIR__ . '/BaseModel.php';

class User extends BaseModel {
    
    protected $table = 'users';
    
    public function create($email, $password, $role = 'customer') {
        $email = strtolower($email);
        $passwordHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        
        $sql = "INSERT INTO users (email, password_hash, role) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 'sss', $email, $passwordHash, $role);
        
        if (mysqli_stmt_execute($stmt)) {
            return mysqli_insert_id($this->conn);
        }
        return false;
    }
    
    public function findByEmail($email) {
        // Case-insensitive email match
        $sql = "SELECT * FROM users WHERE LOWER(email) = LOWER(?) LIMIT 1";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }
    
    public function verifyPassword($email, $password) {
        $user = $this->findByEmail($email);
        
        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        return false;
    }
    
    public function emailExists($email) {
        $sql = "SELECT id FROM users WHERE email = ? LIMIT 1";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        return mysqli_stmt_num_rows($stmt) > 0;
    }
    
    public function getAllCustomers() {
        $sql = "SELECT id, email, role, created_at FROM users WHERE role = 'customer' ORDER BY created_at DESC";
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function getAllUsers() {
        $sql = "SELECT id, email, role, created_at FROM users ORDER BY created_at DESC";
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function updateRole(int $userId, string $role): bool {
        $role = $role === 'admin' ? 'admin' : 'customer';
        $sql = "UPDATE users SET role = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 'si', $role, $userId);
        return mysqli_stmt_execute($stmt);
    }
}