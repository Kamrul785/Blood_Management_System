<?php
// login_register.php
session_start();
require 'inc/db_connect.php';
require 'inc/functions.php';
$hospitals = getHospitals($pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login / Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/custom.css" rel="stylesheet">
</head>
<body>
  <div class="login-bg"></div>
  <div class="auth-box">
    <ul class="nav nav-tabs" id="authTab" role="tablist">
      <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#login-tab">Login</button></li>
      <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#register-tab">Register as Donor</button></li>
      <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#register-hospital-tab">Register as Hospital</button></li>
    </ul>
    <div class="tab-content p-4">
      <!-- LOGIN -->
      <div class="tab-pane fade show active" id="login-tab">
        <form action="process_login.php" method="POST">
          <div class="mb-3">
            <label for="login-type" class="form-label">I am a</label>
            <select id="login-type" name="user_type" class="form-select">
              <option value="donor">Donor</option>
              <option value="admin">Admin</option>
              <option value="hospital">Hospital</option>
            </select>
          </div>
          <!-- donor -->
          <div id="login-donor-fields">
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" name="password" class="form-control" required>
            </div>
          </div>
          <!-- admin -->
          <div id="login-admin-fields" class="d-none">
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email_admin" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" name="password_admin" class="form-control" required>
            </div>
          </div>
          <!-- hospital -->
          <div id="login-hospital-fields" class="d-none">
            <div class="mb-3">
              <label class="form-label">Hospital</label>
              <select name="hospital_id" class="form-select">
                <option value=\"\">-- choose --</option>
                <?php foreach($hospitals as $h): ?>
                  <option value="<?= $h['hospital_id'] ?>"><?= htmlspecialchars($h['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" name="hospital_password" class="form-control" placeholder="hospitalname">
            </div>
          </div>
          <button type="submit" class="btn btn-danger w-100" >LogIn</button>
        </form>
      </div>

      <!-- REGISTER (donor only) -->
      <div class="tab-pane fade" id="register-tab">
        <form action="process_register.php" method="POST">
          <div class="mb-3"><label class="form-label">Full Name</label><input name="name" class="form-control" required></div>
          <div class="mb-3"><label class="form-label">Date of Birth</label><input type="date" name="dob" class="form-control" required></div>
          <div class="mb-3"><label class="form-label">Gender</label>
            <select name="gender" class="form-select" required>
              <option value="">-- select --</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="mb-3"><label class="form-label">Blood Group</label>
            <select name="blood_group" class="form-select" required>
              <option value=\"\">-- select --</option>
              <option>A+</option><option>A-</option><option>B+</option><option>B-</option>
              <option>O+</option><option>O-</option><option>AB+</option><option>AB-</option>
            </select>
          </div>
          <div class="mb-3"><label class="form-label">Address</label><textarea name="address" class="form-control" rows="2"></textarea></div>
          <div class="mb-3"><label class="form-label">Weight (kg)</label><input type="number" name="weight" class="form-control" required></div>
          <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
          <div class="mb-3"><label class="form-label">Mobile Number</label><input name="mobile" class="form-control" required></div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-danger w-100">Register as Donor</button>
        </form>
      </div>

      <!-- REGISTER HOSPITAL -->
      <div class="tab-pane fade" id="register-hospital-tab">
        <form action="process_register_hospital.php" method="POST">
          <div class="mb-3">
            <label class="form-label">Hospital Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Location</label>
            <input type="text" name="location" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Contact Email</label>
            <input type="email" name="contact_email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Contact Mobile</label>
            <input type="text" name="contact_mobile" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-danger w-100">Register Hospital</button>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
// toggle login forms
const loginType = document.getElementById('login-type');
if (loginType) {
  const donorF = document.getElementById('login-donor-fields');
  const adminF = document.getElementById('login-admin-fields');
  const hospF  = document.getElementById('login-hospital-fields');
  loginType.addEventListener('change', e => {
    donorF.classList.toggle('d-none', e.target.value!=='donor');
    adminF.classList.toggle('d-none', e.target.value!=='admin');
    hospF .classList.toggle('d-none', e.target.value!=='hospital');
  });
}
  </script>
</body>
</html>
