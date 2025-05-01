<?php
// donate.php
session_start();
if (!isset($_SESSION['id'], $_SESSION['role']) || $_SESSION['role'] !== 'donor') {
    header('Location: login_register.php');
    exit;
}
require 'inc/db_connect.php';
require 'inc/functions.php';

$donorId   = $_SESSION['id'];
// Fetch donor profile
$stmt = $pdo->prepare('SELECT name, dob, weight, blood_group FROM donors WHERE donor_id = ?');
$stmt->execute([$donorId]);
$donor = $stmt->fetch();

// Calculate age
$dob   = new DateTime($donor['dob']);
$now   = new DateTime();
$age   = $now->diff($dob)->y;

// Fetch hospitals list
$hospitals = getHospitals($pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Donate Blood</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/custom.css" rel="stylesheet">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand text-danger" href="home.php">BloodMS</a>
      <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
          <li class="nav-item"><a class="nav-link" href="logout.php">Log Out</a></li>
          <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container mt-5">
    <h2 class="mb-4">Donate Blood</h2>

    <?php if (!empty($_SESSION['donate_error'])): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['donate_error']) ?></div>
      <?php unset($_SESSION['donate_error']); ?>
    <?php endif; ?>

    <form action="process_donate.php" method="POST">
      <div class="mb-3">
        <label for="hospital" class="form-label">Select Hospital</label>
        <select class="form-select" id="hospital" name="hospital_id" required>
          <option value="">-- choose hospital --</option>
          <?php foreach ($hospitals as $h): ?>
            <option value="<?= $h['hospital_id'] ?>"><?= htmlspecialchars($h['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="date" class="form-label">Donation Date</label>
          <input type="date" id="date" name="donation_date" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
          <label for="time" class="form-label">Donation Time</label>
          <input type="time" id="time" name="donation_time" class="form-control" required>
        </div>
      </div>

      <fieldset disabled class="border p-3 mb-3">
        <legend class="w-auto px-2">Your Details</legend>
        <p><strong>Name:</strong> <?= htmlspecialchars($donor['name']) ?></p>
        <p><strong>Age:</strong> <?= $age ?></p>
        <p><strong>Blood Group:</strong> <?= htmlspecialchars($donor['blood_group']) ?></p>
        <p><strong>Weight:</strong> <?= htmlspecialchars($donor['weight']) ?> kg</p>
      </fieldset>

      <button type="submit" class="btn btn-danger">Confirm Donation</button>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/main.js"></script>
</body>
</html>