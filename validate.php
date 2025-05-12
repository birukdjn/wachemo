<?php
// validate.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include the database connection file
    require_once '../wachemo/assets/database/db-connection.php';

    // Sanitize and validate input data
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Check if fields are empty
    if (empty($username) || empty($password)) {
        echo "Username and password are required.";
        exit;
    }

    // Query to check user credentials
    $stmt = $pdo->prepare("SELECT password, role FROM users WHERE username = :username");
    $stmt->execute([':username' => $username]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Redirect to dashboard based on role
        switch ($user['role']) {
            case 'admin':
                header("Location: /admin.php");
                break;
            case 'teacher':
                header("Location: /teacher.php");
                break;
            case 'student':
                header("Location: /student.php");
                break;
            case 'parent':
                header("Location: /parent.php");
                break;
            default:
                header("location: /index.html");
                exit;
        }
        exit;
    } else {
        echo "Invalid username or password.";
    }
}