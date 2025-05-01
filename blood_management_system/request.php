<?php
// request.php
session_start();
require 'inc/db_connect.php';

$role   = $_SESSION['role'] ?? 'guest';
$userId = $_SESSION['id'] ?? null;

// Prepare user info
$name = $email = $mobile = $address = '';
if ($role === 'donor') {
    $stmt = $pdo->prepare('SELECT name, address, email, mobile FROM donors WHERE donor_id = ?');
    $stmt->execute([$userId]);
    $u = $stmt->fetch();
    [$name, $address, $email, $mobile] = [$u['name'], $u['address'], $u['email'], $u['mobile']];
} elseif ($role === 'admin') {
    $stmt = $pdo->prepare('SELECT name, email, mobile FROM admins WHERE admin_id = ?');
    $stmt->execute([$userId]);
    $u = $stmt->fetch();
    [$name, $email, $mobile] = [$u['name'], $u['email'], $u['mobile']];
} elseif ($role === 'hospital') {
    $stmt = $pdo->prepare('SELECT name, location, contact_email, contact_mobile FROM hospitals WHERE hospital_id = ?');
    $stmt->execute([$userId]);
    $u = $stmt->fetch();
    [$name, $address, $email, $mobile] = [$u['name'], $u['location'], $u['contact_email'], $u['contact_mobile']];
}

$bloodTypes = ['A+','A-','B+','B-','O+','O-','AB+','AB-'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Request Blood</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand text-danger" href="home.php">BloodMS</a>
      <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">
          <?php if($role!=='guest'): ?>
            <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
            <li class="nav-item"><a class="nav-link" href="logout.php">Log Out</a></li>
            <?php endif; ?>
          <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container mt-5">
    <h2>Request Blood</h2>
    <?php if (!empty($_SESSION['request_error'])): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['request_error']) ?></div>
      <?php unset($_SESSION['request_error']); ?>
    <?php endif; ?>

    <form action="process_request.php" method="POST">
      <?php if ($role === 'guest'): ?>
        <!-- Guest fields -->
        <div class="mb-3">
          <label for="guest-name" class="form-label">Full Name</label>
          <input type="text" id="guest-name" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="guest-location" class="form-label">Current Location</label>
          <input type="text" id="guest-location" name="current_location" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="guest-email" class="form-label">Email</label>
          <input type="email" id="guest-email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="guest-mobile" class="form-label">Mobile-money Number</label>
          <input type="tel" id="guest-mobile" name="mobile" class="form-control" required>
        </div>
      <?php elseif ($role === 'hospital'): ?>
        <!-- Hospital fields (auto-filled) -->
        <fieldset disabled class="border p-3 mb-4">
          <legend class="w-auto px-2">Hospital Info</legend>
          <p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
          <p><strong>Location:</strong> <?= htmlspecialchars($address) ?></p>
          <p><strong>Contact:</strong> <?= htmlspecialchars($mobile) ?></p>
        </fieldset>
      <?php else: ?>
        <!-- Donor/Admin (auto-filled) -->
        <fieldset disabled class="border p-3 mb-4">
          <legend class="w-auto px-2">Your Info</legend>
          <p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
          <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
          <p><strong>Mobile:</strong> <?= htmlspecialchars($mobile) ?></p>
          <?php if ($address): ?>
            <p><strong>Address:</strong> <?= htmlspecialchars($address) ?></p>
          <?php endif; ?>
        </fieldset>
      <?php endif; ?>

      <div class="mb-3">
        <label for="blood-type" class="form-label">Blood Type</label>
        <select id="blood-type" name="blood_type" class="form-select" required>
          <option value="">-- select --</option>
          <?php foreach ($bloodTypes as $bt): ?>
            <option value="<?= $bt ?>"><?= $bt ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label for="quantity" class="form-label">Quantity (bags)</label>
        <input type="number" id="quantity" name="quantity" class="form-control" min="1" required>
      </div>

      <div class="mb-3">
        <label for="needed-location" class="form-label">Needed Location</label>
        <input type="text" id="needed-location" name="needed_location" class="form-control" required>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="date-needed" class="form-label">Date Needed</label>
          <input type="date" id="date-needed" name="date_needed" class="form-control" required>
        </div>
        <?php if ($role !== 'hospital'): ?>
          <div class="col-md-6 mb-3">
            <label for="payment-mobile" class="form-label">Payment Mobile-money #</label>
            <input type="tel" id="payment-mobile" name="payment_mobile" class="form-control" required>
          </div>
        <?php endif; ?>
      </div>

      <div class="form-check mb-4">
        <input type="checkbox" id="emergency" name="emergency" class="form-check-input">
        <label for="emergency" class="form-check-label">Emergency Delivery (≤6 hrs) +1 000 ৳</label>
      </div>

      <button type="submit" class="btn btn-danger">Submit Request</button>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/main.js"></script>
</body>
</html>