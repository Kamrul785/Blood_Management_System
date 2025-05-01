<?php
session_start();
require 'inc/db_connect.php';

// Check if there's a confirmation in session
if (!isset($_SESSION['request_confirmation'])) {
    header('Location: request.php');
    exit;
}

$confirmation = $_SESSION['request_confirmation'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Confirmation - BloodMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <style>
        .confirmation-box {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .confirmation-icon {
            font-size: 4rem;
            color: #28a745;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand text-danger" href="home.php">BloodMS</a>
            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
                    <?php if (isset($_SESSION['id'])): ?>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Log Out</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="confirmation-box bg-white text-center">
            <div class="confirmation-icon">✓</div>
            <h2 class="mb-4">Request Submitted Successfully!</h2>
            
            <div class="text-start mb-4">
                <h4>Request Details:</h4>
                <table class="table">
                    <tr>
                        <th>Request ID:</th>
                        <td><?= htmlspecialchars($confirmation['request_id']) ?></td>
                    </tr>
                    <tr>
                        <th>Blood Type:</th>
                        <td><?= htmlspecialchars($confirmation['blood_type']) ?></td>
                    </tr>
                    <tr>
                        <th>Quantity:</th>
                        <td><?= htmlspecialchars($confirmation['quantity']) ?> units</td>
                    </tr>
                    <tr>
                        <th>Date Needed:</th>
                        <td><?= htmlspecialchars($confirmation['date_needed']) ?></td>
                    </tr>
                    <tr>
                        <th>Emergency:</th>
                        <td><?= $confirmation['emergency'] ? 'Yes' : 'No' ?></td>
                    </tr>
                    <tr>
                        <th>Total Cost:</th>
                        <td>৳<?= number_format($confirmation['total_cost']) ?></td>
                    </tr>
                </table>

                <h4>Contact Information:</h4>
                <table class="table">
                    <tr>
                        <th>Name:</th>
                        <td><?= htmlspecialchars($confirmation['name']) ?></td>
                    </tr>
                    <tr>
                        <th>Location:</th>
                        <td><?= htmlspecialchars($confirmation['location']) ?></td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td><?= htmlspecialchars($confirmation['email']) ?></td>
                    </tr>
                    <tr>
                        <th>Mobile:</th>
                        <td><?= htmlspecialchars($confirmation['mobile']) ?></td>
                    </tr>
                </table>
            </div>

            <div class="alert alert-info">
                <p class="mb-0">We will contact you shortly to confirm your request and provide further details.</p>
            </div>

            <div class="mt-4">
                <a href="home.php" class="btn btn-primary">Return to Home</a>
                <a href="request.php" class="btn btn-outline-primary ms-2">Make Another Request</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
// Clear the confirmation data from session
unset($_SESSION['request_confirmation']);
?> 