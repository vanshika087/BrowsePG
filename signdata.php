<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT); // Secure hashing
    $phone = trim($_POST['phone']);
    $gender = trim($_POST['gender']);
    $user_type = trim($_POST['user_type']); // Get user type (tenant or owner)

    // Check if the user already exists
    $check_sql = "SELECT id FROM users WHERE email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        header("Location: index.php?error=email_exists");
        exit();
    }
    $check_stmt->close();

    // Insert user into the database
    $sql = "INSERT INTO users (name, email, password, phone, gender, user_type) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("ssssss", $name, $email, $password, $phone, $gender, $user_type);

    if ($stmt->execute()) {
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_type'] = $user_type;

        // Redirect to index.php after successful signup
        header("Location: index.php");
        exit();
    } else {
        header("Location: index.php?error=signup_failed");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
