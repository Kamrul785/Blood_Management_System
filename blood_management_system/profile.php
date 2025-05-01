<?php
declare(strict_types=1);
// profile.php
session_start();
if (!isset($_SESSION['id'], $_SESSION['role'])) {
    header('Location: login_register.php');
    exit;
}
require 'inc/db_connect.php';
$role = $_SESSION['role'];
$id = $_SESSION['id'];


// Initialize placeholders
$user = [];
$appointments = [];

if ($role === 'donor') {
    // Fetch donor info
    $stmt = $pdo->prepare('SELECT donor_id AS id, name, dob, gender, blood_group, address, weight, email, mobile, points, last_donation_date
                           FROM donors
                           WHERE donor_id = ?');
    $stmt->execute([$_SESSION['id']]);
    $user = $stmt->fetch();
    
    // Detailed debugging
    error_log("Donor ID: " . $_SESSION['id']);
    error_log("Gender value: " . ($user['gender'] ?? 'NULL'));
    error_log("Full user data: " . print_r($user, true));
    
    // Fetch donor appointments
    $stmt = $pdo->prepare('SELECT a.appointment_id, a.date_time, h.name AS hospital
                           FROM appointments a
                           JOIN hospitals h ON a.hospital_id = h.hospital_id
                           WHERE a.donor_id = ?
                           ORDER BY a.date_time ASC');
    $stmt->execute([$_SESSION['id']]);
    $appointments = $stmt->fetchAll();

} elseif ($role === 'hospital') {
    // Fetch hospital info
    $stmt = $pdo->prepare('SELECT hospital_id AS id, name, location, contact_email, contact_mobile
                           FROM hospitals
                           WHERE hospital_id = ?');
    $stmt->execute([$_SESSION['id']]);
    $user = $stmt->fetch();

} elseif ($role === 'admin') {
    // Fetch admin info
    $stmt = $pdo->prepare('SELECT admin_id AS id, email
                           FROM admins
                           WHERE admin_id = ?');
    $stmt->execute([$_SESSION['id']]);
    $user = $stmt->fetch();

} else {
    header('Location: login_register.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile - BloodMS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/custom.css" rel="stylesheet">
  <style>
    .profile-header { padding: 2rem 0; }
    .profile-section { padding: 1.5rem 0; }
  </style>
</head>
<body>
  <!-- Navbar similar to home -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand text-danger" href="home.php">BloodMS</a>
      <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="logout.php">Log Out</a></li>
          <?php if ($role === 'admin'): ?>
            <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container">
    <!-- Top Section (1/3) -->
    <div class="row profile-header text-center bg-white rounded shadow-sm mb-4">
      <div class="col">
        <h2><?= $role === 'donor' ? htmlspecialchars($user['name']) : ($role==='hospital'?htmlspecialchars($user['name']):'Admin') ?></h2>
        <?php if ($role === 'donor'): ?>
          <p><strong>ID:</strong> <?= $user['id'] ?> | <strong>Blood Group:</strong> <?= htmlspecialchars($user['blood_group']) ?> | <strong>Points:</strong> <?= $user['points'] ?></p>
        <?php elseif ($role === 'hospital'): ?>
          <p><strong>ID:</strong> <?= $user['id'] ?> | <strong>Location:</strong> <?= htmlspecialchars($user['location']) ?></p>
        <?php else: ?>
          <p><strong>ID:</strong> <?= $user['id'] ?> | <strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Middle Section (1/3): Detailed Info -->
    <div class="profile-section bg-white rounded shadow-sm mb-4">
      <h4>Details</h4>
      <table class="table">
        <tbody>
          <?php if ($role === 'donor'): ?>
          <tr><th>Name</th><td><?= htmlspecialchars($user['name'] ?? '') ?></td></tr>
          <tr><th>DOB</th><td><?= htmlspecialchars($user['dob'] ?? '') ?></td></tr>
          <tr><th>Gender</th><td><?= htmlspecialchars(ucfirst($user['gender'] ?? '')) ?></td></tr>
          <tr><th>Address</th><td><?= htmlspecialchars($user['address'] ?? '') ?></td></tr>
          <tr><th>Weight</th><td><?= htmlspecialchars((string)($user['weight'] ?? '')) ?> kg</td></tr>
          <tr><th>Email</th><td><?= htmlspecialchars($user['email'] ?? '') ?></td></tr>
          <tr><th>Mobile</th><td><?= htmlspecialchars($user['mobile'] ?? '') ?></td></tr>
          <tr><th>Last Donation</th><td><?= htmlspecialchars($user['last_donation_date'] ?? 'Not available') ?></td></tr>
          <?php elseif ($role === 'hospital'): ?>
          <tr><th>Hospital Name</th><td><?= htmlspecialchars($user['name'] ?? '') ?></td></tr>
          <tr><th>Location</th><td><?= htmlspecialchars($user['location'] ?? '') ?></td></tr>
          <tr><th>Email</th><td><?= htmlspecialchars($user['contact_email'] ?? '') ?></td></tr>
          <tr><th>Mobile</th><td><?= htmlspecialchars($user['contact_mobile'] ?? '') ?></td></tr>
          <?php else: ?>
          <tr><th>Admin Email</th><td><?= htmlspecialchars($user['email'] ?? '') ?></td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Bottom Section (1/3): Appointments or Placeholder -->
    <div class="profile-section bg-white rounded shadow-sm mb-4">
      <h4>Upcoming Appointments</h4>
      <?php if ($role === 'donor' && count($appointments) > 0): ?>
        <ul class="list-group">
          <?php foreach ($appointments as $appt): ?>
            <li class="list-group-item d-flex justify-content-between">
              <span><?= htmlspecialchars($appt['hospital']) ?></span>
              <span><?= date('M j, Y - g:i a', strtotime($appt['date_time'])) ?></span>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p class="text-muted">No upcoming appointments.</p>
      <?php endif; ?>
    </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/main.js"></script>
</body>
</html>
