<?php

// Iniciar sesión
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sarah_app');

// App Root
define('APP_ROOT', dirname(dirname(__FILE__)));

// URL Root
define('BASE_URL', 'http://localhost/ayudame.pe/public/');

// Site Name
define('SITE_NAME', 'CachueApp');

// App Version
define('APP_VERSION', '1.0.0');

// Upload directories
define('UPLOAD_DIR', 'uploads/');
define('EVIDENCE_DIR', 'uploads/evidence/');
define('PROFILE_DIR', 'uploads/profiles/');

// Helper Functions
function redirect($location) {
    header('Location: ' . BASE_URL . $location);
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        redirect('auth/login');
    }
}

function requireRole($role) {
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== $role) {
        redirect('dashboard');
    }
}

function requireProfileComplete() {
    if (!isset($_SESSION['is_profile_complete']) || !$_SESSION['is_profile_complete']) {
        redirect('profile/complete');
    }
}

function flashMessage($type, $message) {
    $_SESSION['flash_' . $type] = $message;
}

function getFlashMessage($type) {
    if (isset($_SESSION['flash_' . $type])) {
        $message = $_SESSION['flash_' . $type];
        unset($_SESSION['flash_' . $type]);
        return $message;
    }
    return null;
}

function formatCurrency($amount) {
    return '$' . number_format($amount, 2);
}

function timeAgo($datetime) {
    $time = time() - strtotime($datetime);
    
    if ($time < 60) return 'hace ' . $time . ' segundos';
    if ($time < 3600) return 'hace ' . round($time/60) . ' minutos';
    if ($time < 86400) return 'hace ' . round($time/3600) . ' horas';
    if ($time < 2592000) return 'hace ' . round($time/86400) . ' días';
    if ($time < 31536000) return 'hace ' . round($time/2592000) . ' meses';
    return 'hace ' . round($time/31536000) . ' años';
}

function uploadFile($file, $directory, $allowedTypes = ['jpg', 'jpeg', 'png', 'gif']) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, $allowedTypes)) {
        return false;
    }
    
    if (!file_exists($directory)) {
        mkdir($directory, 0777, true);
    }
    
    $filename = time() . '_' . uniqid() . '.' . $extension;
    $filepath = $directory . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return $filepath;
    }
    
    return false;
}

function validateRequired($fields, $data) {
    $errors = [];
    foreach ($fields as $field) {
        if (!isset($data[$field]) || empty(trim($data[$field]))) {
            $errors[] = "El campo {$field} es requerido";
        }
    }
    return $errors;
}

function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

function generateToken() {
    return bin2hex(random_bytes(32));
}