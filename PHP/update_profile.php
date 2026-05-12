<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// ── Auth guard ──
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// ── Only accept POST ──
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: user_profile.php');
    exit;
}

require_once __DIR__ . '/../config/db.php';
$pdo = getPDO();

$userId = (int) $_SESSION['user_id'];
$name   = trim($_POST['name']  ?? '');
$email  = trim($_POST['email'] ?? '');

// ── Basic validation ──
if ($name === '' || $email === '') {
    $_SESSION['error_msg'] = 'Name and email are required.';
    header('Location: user_profile.php');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error_msg'] = 'Please enter a valid email address.';
    header('Location: user_profile.php');
    exit;
}

// ── Check email not already taken by another user ──
$check = $pdo->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
$check->execute([$email, $userId]);

if ($check->fetchColumn()) {
    $_SESSION['error_msg'] = 'That email address is already in use by another account.';
    header('Location: user_profile.php');
    exit;
}

// ── Update DB ──
$stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE user_id = ?");

if ($stmt->execute([$name, $email, $userId])) {
    $_SESSION['user_name']   = $name;
    $_SESSION['user_email']  = $email;
    $_SESSION['success_msg'] = 'Profile updated successfully!';
} else {
    $_SESSION['error_msg'] = 'Failed to update profile. Please try again.';
}

header('Location: user_profile.php');
exit;