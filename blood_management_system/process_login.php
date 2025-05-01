<?php
// process_login.php
session_start();
require 'inc/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login_register.php');
    exit;
}

// Determine type
$type = $_POST['user_type'] ?? '';
$error = '';

switch ($type) {
    case 'donor':
        $email = trim($_POST['email']);
        $pwd = $_POST['password'];
        
        if (empty($email) || empty($pwd)) {
            $error = 'Please enter both email and password';
            break;
        }

        $stmt = $pdo->prepare('SELECT donor_id, password_hash FROM donors WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            $error = 'Invalid email or password';
        } elseif (!password_verify($pwd, $user['password_hash'])) {
            $error = 'Invalid email or password';
        } else {
            $_SESSION = ['id' => $user['donor_id'], 'role' => 'donor'];
            header('Location: home.php');
            exit;
        }
        break;

    case 'admin':
        $email = trim($_POST['email_admin']);
        $pwd = $_POST['password_admin'];
        
        if (empty($email) || empty($pwd)) {
            $error = 'Please enter both email and password';
            break;
        }

        $stmt = $pdo->prepare('SELECT admin_id, password_hash FROM admins WHERE email = ?');
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        if (!$admin) {
            $error = 'Invalid email or password';
        } elseif (!password_verify($pwd, $admin['password_hash'])) {
            $error = 'Invalid email or password';
        } else {
            $_SESSION = ['id' => $admin['admin_id'], 'role' => 'admin'];
            header('Location: home.php');
            exit;
        }
        break;

    case 'hospital':
        $hid = $_POST['hospital_id'];
        $pw = $_POST['hospital_password'];
        
        if (empty($hid) || empty($pw)) {
            $error = 'Please select hospital and enter password';
            break;
        }

        $stmt = $pdo->prepare('SELECT hospital_id, password_hash FROM hospitals WHERE hospital_id = ?');
        $stmt->execute([$hid]);
        $h = $stmt->fetch();

        if (!$h) {
            $error = 'Invalid hospital or password';
        } elseif (!password_verify($pw, $h['password_hash'])) {
            $error = 'Invalid hospital or password';
        } else {
            $_SESSION = ['id' => $h['hospital_id'], 'role' => 'hospital'];
            header('Location: home.php');
            exit;
        }
        break;

    default:
        $error = 'Please select a user type';
}

// If we get here, there was an error
$_SESSION['login_error'] = $error;
header('Location: login_register.php');
exit;