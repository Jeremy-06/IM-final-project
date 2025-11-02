<?php

class Session {
    
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }
    
    public static function get($key, $default = null) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }
    
    public static function has($key) {
        return isset($_SESSION[$key]);
    }
    
    public static function remove($key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    
    public static function destroy() {
        session_destroy();
    }
    
    public static function setFlash($key, $message) {
        $_SESSION['flash'][$key] = $message;
    }
    
    public static function getFlash($key) {
        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        return null;
    }
    
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    public static function isAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }
    
    public static function getUserId() {
        return self::get('user_id');
    }
    
    public static function getEmail() {
        return self::get('email');
    }
    
    public static function getFirstName() {
        return self::get('first_name');
    }
    
    /**
     * Check if the currently logged-in user still exists in database
     * If user was deleted, destroy session and redirect to login
     */
    public static function validateUserExists() {
        if (!self::isLoggedIn()) {
            return true; // Not logged in, nothing to validate
        }
        
        require_once __DIR__ . '/../models/User.php';
        $userModel = new User();
        $userId = self::getUserId();
        $user = $userModel->findById($userId);
        
        if (!$user) {
            // User was deleted, destroy session and redirect
            self::destroy();
            self::setFlash('message', 'Your account has been deleted. Please contact support if you believe this is an error.');
            header('Location: index.php?page=login');
            exit();
        }
        
        return true;
    }
}