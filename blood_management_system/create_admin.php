<?php
require 'inc/db_connect.php';

// Admin credentials
$admin_data = [
    'name' => 'System Admin',
    'email' => 'admin@bloodms.com',
    'mobile' => '1234567890',
    'password' => 'admin123'  // You should change this after first login
];

try {
    // Check if admin already exists
    $stmt = $pdo->prepare('SELECT admin_id FROM admins WHERE email = ?');
    $stmt->execute([$admin_data['email']]);
    $existing_admin = $stmt->fetch();

    if (!$existing_admin) {
        // Create admin account
        $password_hash = password_hash($admin_data['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO admins (name, email, mobile, password_hash) VALUES (?, ?, ?, ?)');
        $stmt->execute([
            $admin_data['name'],
            $admin_data['email'],
            $admin_data['mobile'],
            $password_hash
        ]);
        
        echo "Admin account created successfully!<br>";
        echo "Email: " . $admin_data['email'] . "<br>";
        echo "Password: " . $admin_data['password'] . "<br>";
        echo "Please change the password after first login.";
    } else {
        echo "Admin account already exists!";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 