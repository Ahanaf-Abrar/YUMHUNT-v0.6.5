<?php
// Start or resume the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Function to set a session variable
function setSessionVariable($key, $value) {
    $_SESSION[$key] = $value;
}

// Function to get a session variable
function getSessionVariable($key) {
    return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
}

// Function to check if a session variable exists
function sessionVariableExists($key) {
    return isset($_SESSION[$key]);
}

// Function to remove a session variable
function removeSessionVariable($key) {
    if (isset($_SESSION[$key])) {
        unset($_SESSION[$key]);
    }
}

// Function to clear all session variables
function clearAllSessionVariables() {
    session_unset();
}

// Function to destroy the session
function destroySession() {
    session_destroy();
}

// Function to regenerate session ID (for security)
function regenerateSessionId() {
    session_regenerate_id(true);
}

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) || isset($_SESSION['admin_id']);
}

// Function to check if admin is logged in
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

// Function to get current user ID (works for both regular users and admins)
function getCurrentUserId() {
    if (isset($_SESSION['user_id'])) {
        return $_SESSION['user_id'];
    } elseif (isset($_SESSION['admin_id'])) {
        return $_SESSION['admin_id'];
    }
    return null;
}

// Function to set login expiration
function setLoginExpiration($minutes = 30) {
    $_SESSION['login_expiration'] = time() + ($minutes * 60);
}

// Function to check if login has expired
function isLoginExpired() {
    if (!isset($_SESSION['login_expiration'])) {
        return true;
    }
    return time() > $_SESSION['login_expiration'];
}

// Example usage of setting login expiration (call this after successful login)
// setLoginExpiration(30); // Set expiration to 30 minutes from now
