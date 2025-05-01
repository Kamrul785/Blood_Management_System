<?php
// process_donate.php
session_start();
if (!isset($_SESSION['id'], $_SESSION['role']) || $_SESSION['role'] !== 'donor') {
    header('Location: login_register.php');
    exit;
}
require 'inc/db_connect.php';
require 'inc/functions.php';

$donorId    = $_SESSION['id'];
$hospitalId = $_POST['hospital_id'] ?? '';
$date       = $_POST['donation_date'] ?? '';
$time       = $_POST['donation_time'] ?? '';

// Validate inputs
if (!$hospitalId || !$date || !$time) {
    $_SESSION['donate_error'] = 'Please fill out all fields.';
    header('Location: donate.php');
    exit;
}

// Fetch donor record
$stmt = $pdo->prepare('SELECT dob, weight, last_donation_date, points FROM donors WHERE donor_id = ?');
$stmt->execute([$donorId]);
$d = $stmt->fetch();
if (!$d) {
    $_SESSION['donate_error'] = 'Donor not found.';
    header('Location: donate.php');
    exit;
}

// Eligibility: age >= 18, weight >= 50kg, 3 months since last donation
$dobObj = new DateTime($d['dob']);
$now    = new DateTime();
$age    = $now->diff($dobObj)->y;
if ($age < 18 || $d['weight'] < 50) {
    $_SESSION['donate_error'] = 'Not eligible: age or weight criteria not met.';
    header('Location: donate.php');
    exit;
}

if ($d['last_donation_date']) {
    $lastDt   = new DateTime($d['last_donation_date']);
    $interval = $now->diff($lastDt);
    $months   = $interval->y * 12 + $interval->m;
    if ($months < 3) {
        $_SESSION['donate_error'] = 'Please wait 3 months between donations.';
        header('Location: donate.php');
        exit;
    }
}

// Insert appointment
$dt = "$date $time:00";
$stmt = $pdo->prepare('INSERT INTO appointments (donor_id, hospital_id, date_time) VALUES (?, ?, ?)');
$stmt->execute([$donorId, $hospitalId, $dt]);

// Award points and update last donation
$stmt = $pdo->prepare('UPDATE donors SET points = points + 10, last_donation_date = ? WHERE donor_id = ?');
$stmt->execute([$dt, $donorId]);

// Fetch updated points & hospital name
$newPts = $pdo->query("SELECT points FROM donors WHERE donor_id = $donorId")->fetchColumn();
$hName  = $pdo->prepare('SELECT name FROM hospitals WHERE hospital_id = ?');
$hName->execute([$hospitalId]);
$hos    = $hName->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Donation Confirmation</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand text-danger" href="home.php">BloodMS</a>
    </div>
  </nav>

  <div class="container mt-5">
    <h2>Donation Confirmed</h2>
    <div class="alert alert-success">
      <p>Your appointment at <strong><?= htmlspecialchars($hos) ?></strong> on <strong><?= date('M j, Y', strtotime($dt)) ?></strong> at <strong><?= date('g:i a', strtotime($dt)) ?></strong> is confirmed.</p>
      <p><strong>Points earned:</strong> +10</p>
      <p><strong>Total points:</strong> <?= $newPts ?></p>
    </div>
    <a href="home.php" class="btn btn-danger">Back to Home</a>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/main.js"></script>
</body>
</html>