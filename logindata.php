<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $user_type = trim($_POST['user_type']); // Get user type from form

    // Check if user exists with given email and user type
    $sql = "SELECT id, name, email, password, user_type FROM users WHERE email = ? AND user_type = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $user_type);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Store user info in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_type'] = $user['user_type'];

            // Redirect to index.php after successful login
            header("Location: index.php");
            exit();
        } else {
            header("Location: index.php?error=invalid_password");
            exit();
        }
    } else {
        header("Location: index.php?error=user_not_found");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
