<?php
session_start();
require_once __DIR__ . '/includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Please enter email and password.';
    } else {
        $pdo = getPDO();
        $stmt = $pdo->prepare("SELECT user_id, name, password_hash FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $error = 'Invalid email or password.';
        } else {
            $_SESSION['user_id'] = (int)$user['user_id'];
            $_SESSION['user_name'] = $user['name'];

            header("Location: dashboard.php");
            exit;
        }
    }
}
// If GET or error, show UI and echo $error somewhere.
