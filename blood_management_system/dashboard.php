<?php
// dashboard.php
session_start();
if (!isset($_SESSION['id'], $_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login_register.php'); exit;
}
require 'inc/db_connect.php';
require 'inc/functions.php';

// Data for sections
$inventory  = getInventory($pdo);
$events       = getEvents($pdo);
$donorsStmt  = $pdo->query('SELECT donor_id AS id,name,blood_group,address,email,points FROM donors');
$donors      = $donorsStmt->fetchAll();
$hospStmt     = $pdo->query('SELECT hospital_id AS id,name,location FROM hospitals');
$hospitals    = $hospStmt->fetchAll();
$reqStmt     = $pdo->query('SELECT * FROM blood_requests ORDER BY date_needed DESC');
$requests    = $reqStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand text-danger" href="home.php">BloodMS</a>
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">Log Out</a></li>
      </ul>
    </div>
  </nav>
  <div class="container mt-5">
    <h2>Admin Dashboard</h2>
    <!-- Inventory Management -->
    <section class="mb-5">
      <h4>Inventory Management</h4>
      <table class="table table-bordered">
        <thead><tr><th>Blood Type</th><th>Quantity</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach($inventory as $type=>$qty): ?>
          <tr>
            <td><?= $type ?></td>
            <td><?= $qty ?></td>
            <td><button class="btn btn-sm btn-outline-primary">Edit</button></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </section>

    <!-- Events & Announcements -->
    <section class="mb-5">
      <h4>Events & Announcements</h4>
      <button class="btn btn-danger mb-3">Create New</button>
      <ul class="list-group">
        <?php foreach($events as $e): ?>
          <li class="list-group-item d-flex justify-content-between">
            <?= htmlspecialchars($e['title']) ?>
            <div>
              <button class="btn btn-sm btn-outline-secondary">Edit</button>
              <button class="btn btn-sm btn-outline-danger">Delete</button>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
    </section>

    <!-- User Management -->
    <section class="mb-5">
      <h4>User Management</h4>
      <h5>Donors</h5>
      <table class="table table-striped">
        <thead><tr><th>Name</th><th>Blood Group</th><th>Location</th><th>Email</th><th>Points</th><th>Action</th></tr></thead>
        <tbody>
        <?php foreach($donors as $d): ?>
          <tr>
            <td><?= htmlspecialchars($d['name']) ?></td>
            <td><?= $d['blood_group'] ?></td>
            <td><?= htmlspecialchars($d['address']) ?></td>
            <td><?= htmlspecialchars($d['email']) ?></td>
            <td><?= $d['points'] ?></td>
            <td><button class="btn btn-sm btn-outline-danger">Delete</button></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <h5>Hospitals</h5>
      <button class="btn btn-danger mb-3">Add Hospital</button>
      <table class="table table-striped">
        <thead><tr><th>Name</th><th>Location</th><th>Action</th></tr></thead>
        <tbody>
        <?php foreach($hospitals as $h): ?>
          <tr>
            <td><?= htmlspecialchars($h['name']) ?></td>
            <td><?= htmlspecialchars($h['location']) ?></td>
            <td><button class="btn btn-sm btn-outline-danger">Delete</button></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </section>

    <!-- Requests -->
    <section class="mb-5">
      <h4>Blood Requests</h4>
      <table class="table table-hover">
        <thead><tr><th>ID</th><th>Name</th><th>Type</th><th>Qty</th><th>Date Needed</th><th>Emergency</th><th>Total ৳</th></tr></thead>
        <tbody>
        <?php foreach($requests as $r): ?>
          <tr>
            <td><?= $r['request_id'] ?></td>
            <td><?= htmlspecialchars($r['name']) ?></td>
            <td><?= $r['blood_type'] ?></td>
            <td><?= $r['quantity'] ?></td>
            <td><?= date('M j, Y', strtotime($r['date_needed'])) ?></td>
            <td><?= $r['emergency'] ? 'Yes' : 'No' ?></td>
            <td><?= $r['total_cost'] ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </section>

  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/main.js"></script>
</body>
</html>