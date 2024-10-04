<?php
// Start a new session or resume the existing session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Function to set a session variable
function setSession($key, $value) {
    $_SESSION[$key] = $value;
}

// Function to get a session variable
function getSession($key) {
    return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
}

// Function to check if a session variable is set
function hasSession($key) {
    return isset($_SESSION[$key]);
}

// Function to unset a session variable
function unsetSession($key) {
    if (isset($_SESSION[$key])) {
        unset($_SESSION[$key]);
    }
}

// Function to destroy the entire session
function destroySession() {
    session_unset();
    session_destroy();
}
?>
