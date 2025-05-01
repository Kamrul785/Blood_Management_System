<?php
// process_register.php
session_start();
require 'inc/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: login_register.php'); exit;
}
// Sanitize & validate inputs...
$name  = trim($_POST['name']);
$dob   = $_POST['dob'];
$gender= trim($_POST['gender']);
$bg    = $_POST['blood_group'];
$addr  = trim($_POST['address']);
$w     = (float)$_POST['weight'];
$email = trim($_POST['email']);
$mobile= trim($_POST['mobile']);
$password = $_POST['password'];

// Validate gender
$valid_genders = ['male', 'female', 'other'];
if (!in_array($gender, $valid_genders)) {
    $_SESSION['register_error'] = 'Invalid gender selected';
    header('Location: login_register.php');
    exit;
}

// Validate password
if (strlen($password) < 6) {
    $_SESSION['register_error'] = 'Password must be at least 6 characters long';
    header('Location: login_register.php');
    exit;
}

// Debug registration data
error_log("Registration Data - Gender: " . $gender);

try {
    // Check if email already exists
    $stmt = $pdo->prepare('SELECT donor_id FROM donors WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        throw new Exception('Email already registered');
    }

    // Hash password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare('INSERT INTO donors (name,dob,gender,blood_group,address,weight,email,mobile,password_hash) VALUES (?,?,?,?,?,?,?,?,?)');
    $stmt->execute([$name,$dob,$gender,$bg,$addr,$w,$email,$mobile,$password_hash]);

    $_SESSION = ['id'=>$pdo->lastInsertId(),'role'=>'donor'];
    header('Location: home.php');
    exit;

} catch (Exception $e) {
    $_SESSION['register_error'] = $e->getMessage();
    header('Location: login_register.php');
    exit;
}