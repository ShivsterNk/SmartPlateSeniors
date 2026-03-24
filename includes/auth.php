<?php
/**
 * Simple Authentication Helper
 * For testing the chatbot with the test user
 */

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function get_user_id() {
    return $_SESSION['user_id'] ?? null;
}

function get_user_name() {
    return $_SESSION['user_name'] ?? null;
}

function require_login() {
    if (!is_logged_in()) {
        // For testing, auto-login as test user
        // In production, redirect to login page
        $_SESSION['user_id'] = 1; // Test user ID
        $_SESSION['user_name'] = 'Test User';
    }
}
