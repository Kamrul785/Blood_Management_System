<?php
// process_request.php
session_start();
require 'inc/db_connect.php';

$role       = $_SESSION['role'] ?? 'guest';
$userId     = $_SESSION['id']   ?? null;

$bloodType  = $_POST['blood_type']  ?? '';
$quantity   = (int)($_POST['quantity'] ?? 0);
$dateNeeded = $_POST['date_needed'] ?? '';
$emergency  = isset($_POST['emergency']) ? 1 : 0;

// Gather common fields
try {
    if ($role === 'guest') {
        $name        = trim($_POST['name']);
        $location    = trim($_POST['current_location']);
        $email       = trim($_POST['email']);
        $mobile      = trim($_POST['mobile']);
        if (!$name || !$location || !$email || !$mobile) {
            throw new Exception('Please fill out all guest fields.');
        }
    } elseif ($role === 'donor') {
        $stmt = $pdo->prepare('SELECT name, address, email, mobile FROM donors WHERE donor_id = ?');
        $stmt->execute([$userId]);
        $u = $stmt->fetch();
        $name     = $u['name'];
        $location = trim($_POST['needed_location']);
        $email    = $u['email'];
        $mobile   = $u['mobile'];
    } elseif ($role === 'admin') {
        $stmt = $pdo->prepare('SELECT name, email, mobile FROM admins WHERE admin_id = ?');
        $stmt->execute([$userId]);
        $u = $stmt->fetch();
        $name     = $u['name'];
        $location = trim($_POST['needed_location']);
        $email    = $u['email'];
        $mobile   = $u['mobile'];
    } else { // hospital
        $stmt = $pdo->prepare('SELECT name, location, contact_mobile FROM hospitals WHERE hospital_id = ?');
        $stmt->execute([$userId]);
        $u = $stmt->fetch();
        $name     = $u['name'];
        $location = $u['location'];
        $email    = $u['contact_email'];
        $mobile   = $u['contact_mobile'];
    }

    // Validate form basics
    if (!$bloodType || $quantity < 1 || !$dateNeeded) {
        throw new Exception('Please select blood type, quantity, and date.');
    }

    // Compute cost
    $unitCost     = 500;
    $emergencyFee = 1000;
    $total        = $quantity * $unitCost + ($emergency ? $emergencyFee : 0);

    // Insert into DB
    $stmt = $pdo->prepare("
        INSERT INTO blood_requests (
            requester_type, requester_id, name, location, email, mobile,
            blood_type, quantity, date_needed, emergency, total_cost
        ) VALUES (?,?,?,?,?,?,?,?,?,?,?)
    ");
    $stmt->execute([
        $role,
        $role === 'guest' ? null : $userId,
        $name,
        $location,
        $email,
        $mobile,
        $bloodType,
        $quantity,
        $dateNeeded,
        $emergency,
        $total
    ]);

    // Store request details in session for confirmation page
    $_SESSION['request_confirmation'] = [
        'request_id' => $pdo->lastInsertId(),
        'blood_type' => $bloodType,
        'quantity' => $quantity,
        'date_needed' => $dateNeeded,
        'emergency' => $emergency,
        'total_cost' => $total,
        'name' => $name,
        'location' => $location,
        'email' => $email,
        'mobile' => $mobile
    ];

    // Redirect to confirmation page
    header('Location: request_confirmation.php');
    exit;

} catch (Exception $ex) {
    $_SESSION['request_error'] = $ex->getMessage();
    header('Location: request.php');
    exit;
}

// Show confirmation below...
