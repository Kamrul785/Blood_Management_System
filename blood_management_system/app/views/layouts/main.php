<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?></title>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= APP_URL ?>/assets/css/custom.css" rel="stylesheet">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?? '' ?>">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="<?= url('home') ?>">BloodMS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if(isset($_SESSION['user'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= url('profile') ?>">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= url('logout') ?>">Log Out</a>
                        </li>
                        <?php if($_SESSION['user']['role'] === 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= url('dashboard') ?>">Dashboard</a>
                            </li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= url('login') ?>">Sign In</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="bg-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><?= APP_NAME ?></h5>
                    <p class="text-muted">Saving lives through blood donation</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted">&copy; <?= date('Y') ?> <?= APP_NAME ?>. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= APP_URL ?>/assets/js/main.js"></script>
</body>
</html> 