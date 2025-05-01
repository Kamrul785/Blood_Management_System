<?php
session_start();
require 'inc/db_connect.php';
require 'inc/functions.php';

// Fetch data
$events     = getEvents($pdo);
$inventory  = getInventory($pdo);
$role       = $_SESSION['role'] ?? 'guest';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home - Blood Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/custom.css" rel="stylesheet">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
      <a class="navbar-brand" href="home.php">BloodMS</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <?php if(isset($_SESSION['role'])): ?>
            <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
            <li class="nav-item"><a class="nav-link" href="logout.php">Log Out</a></li>
            <?php if($role==='admin'): ?>
              <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
            <?php endif; ?>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="login_register.php">Sign In</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <div class="hero">
    <div class="overlay animate-fade-in">
      <h1>Join Our Life-Saving Mission</h1>
      <p class="lead">Donate blood today, save lives tomorrow.</p>
      <div class="mt-4">
        <?php if($role === 'guest'): ?>
          <a href="login_register.php" class="btn btn-danger me-2">Register to Donate</a>
        <?php else: ?>
          <a href="donate.php" class="btn btn-danger me-2">Donate Blood</a>
        <?php endif; ?>
        <a href="request.php" class="btn btn-outline-light">Request Blood</a>
      </div>
    </div>
  </div>

  <div class="container">
    <!-- Events & Announcements -->
    <section class="mb-5">
      <h2 class="text-center mb-4">Events &amp; Announcements</h2>
      <div class="row">
        <?php foreach($events as $e): ?>
          <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($e['title']) ?></h5>
                <p class="text-muted mb-3">
                  <i class="bi bi-calendar-event me-2"></i>
                  <?= date('M j, Y', strtotime($e['date'])) ?>
                </p>
                <p class="card-text"><?= nl2br(htmlspecialchars($e['message'])) ?></p>
                <?php if($role==='donor'): ?>
                  <form action="book_event.php" method="POST" class="mt-3">
                    <input type="hidden" name="event_id" value="<?= $e['id'] ?>">
                    <button class="btn btn-danger w-100">Book Appointment</button>
                  </form>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- Blood Inventory -->
    <section class="mb-5">
      <h2 class="text-center mb-4">Blood Inventory</h2>
      <div class="row">
        <?php foreach($inventory as $type => $qty): ?>
          <div class="col-6 col-md-3 mb-4">
            <div class="card text-center">
              <div class="card-body">
                <i class="bi bi-droplet-fill display-4 text-danger mb-3"></i>
                <h5 class="card-title"><?= $type ?></h5>
                <p class="card-text display-6"><?= $qty ?></p>
                <small class="text-muted">bags available</small>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/main.js"></script>
</body>
</html>