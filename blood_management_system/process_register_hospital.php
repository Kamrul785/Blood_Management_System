<?php
// process_register_hospital.php
session_start();
require 'inc/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login_register.php');
    exit;
}

try {
    // Sanitize and validate inputs
    $name = trim($_POST['name']);
    $location = trim($_POST['location']);
    $contact_email = trim($_POST['contact_email']);
    $contact_mobile = trim($_POST['contact_mobile']);
    $password = $_POST['password'];

    // Validate inputs
    if (empty($name) || empty($location) || empty($contact_email) || empty($contact_mobile) || empty($password)) {
        throw new Exception('All fields are required');
    }

    // Check if email already exists
    $stmt = $pdo->prepare('SELECT hospital_id FROM hospitals WHERE contact_email = ?');
    $stmt->execute([$contact_email]);
    if ($stmt->fetch()) {
        throw new Exception('Email already registered');
    }

    // Hash password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert hospital
    $stmt = $pdo->prepare('INSERT INTO hospitals (name, location, contact_email, contact_mobile, password_hash) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$name, $location, $contact_email, $contact_mobile, $password_hash]);

    // Get the new hospital ID
    $hospital_id = $pdo->lastInsertId();

    // Set session and redirect
    $_SESSION = ['id' => $hospital_id, 'role' => 'hospital'];
    header('Location: home.php');
    exit;

} catch (Exception $e) {
    $_SESSION['register_error'] = $e->getMessage();
    header('Location: login_register.php');
    exit;
}
?> 