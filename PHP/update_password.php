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

$userId          = (int) $_SESSION['user_id'];
$currentPassword = $_POST['current_password'] ?? '';
$newPassword     = $_POST['new_password']     ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

// ── Basic validation ──
if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
    $_SESSION['error_msg'] = 'All password fields are required.';
    header('Location: user_profile.php');
    exit;
}

if ($newPassword !== $confirmPassword) {
    $_SESSION['error_msg'] = 'New passwords do not match.';
    header('Location: user_profile.php');
    exit;
}

if (strlen($newPassword) < 8) {
    $_SESSION['error_msg'] = 'New password must be at least 8 characters long.';
    header('Location: user_profile.php');
    exit;
}

// ── Fetch current hashed password from DB ──
$stmt = $pdo->prepare("SELECT password_hash FROM users WHERE user_id = ?");
$stmt->execute([$userId]);
$row = $stmt->fetch();

if (!$row) {
    $_SESSION['error_msg'] = 'User not found.';
    header('Location: user_profile.php');
    exit;
}

// ── Verify current password ──
if (!password_verify($currentPassword, $row['password_hash'])) {
    $_SESSION['error_msg'] = 'Current password is incorrect.';
    header('Location: user_profile.php');
    exit;
}

// ── Hash and save new password ──
$hashedNew = password_hash($newPassword, PASSWORD_DEFAULT);

$update = $pdo->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");

if ($update->execute([$hashedNew, $userId])) {
    $_SESSION['success_msg'] = 'Password updated successfully!';
} else {
    $_SESSION['error_msg'] = 'Failed to update password. Please try again.';
}

header('Location: user_profile.php');
exit;