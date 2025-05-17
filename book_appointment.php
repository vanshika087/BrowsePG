<?php
session_start();
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("<script>alert('Error: You must be logged in to book an appointment.'); window.history.back();</script>");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['pg_id'], $_POST['email'], $_POST['date'], $_POST['time'])) {
        die("<script>alert('Error: Missing required fields.'); window.history.back();</script>");
    }

    $user_id = intval($_SESSION['user_id']); // Get from session
    $pg_id = intval($_POST['pg_id']);
    $email = $_POST['email'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    // Ensure user exists in the users table
    $checkUserQuery = "SELECT id FROM users WHERE id = ?";
    $stmt = $conn->prepare($checkUserQuery);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $userResult = $stmt->get_result();

    if ($userResult->num_rows === 0) {
        die("<script>alert('Error: User does not exist.'); window.history.back();</script>");
    }

    // Get owner_id from pg_listings
    $ownerQuery = "SELECT owner_id FROM pg_listings WHERE id = ?";
    $stmt = $conn->prepare($ownerQuery);
    $stmt->bind_param("i", $pg_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $pg = $result->fetch_assoc();

    if (!$pg) {
        die("<script>alert('Error: PG listing not found.'); window.history.back();</script>");
    }

    $owner_id = $pg['owner_id'];

    // Insert into appointments table
    $query = "INSERT INTO appointments (user_id, email, pg_id, owner_id, appointment_date, appointment_time, status) 
              VALUES (?, ?, ?, ?, ?, ?, 'pending')";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("<script>alert('Error preparing query: " . $conn->error . "'); window.history.back();</script>");
    }

    $stmt->bind_param("isisss", $user_id, $email, $pg_id, $owner_id, $date, $time);

    if ($stmt->execute()) {
        echo "<script>alert('Appointment booked successfully!'); window.location.href='pg_details.php?id=$pg_id';</script>";
    } else {
        die("<script>alert('Error Booking Appointment: " . $stmt->error . "'); window.history.back();</script>");
    }

    $stmt->close();
    $conn->close();
}
?>
