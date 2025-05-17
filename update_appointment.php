<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'owner') {
    die("Unauthorized access.");
}

if (!isset($_GET['id']) || !isset($_GET['status'])) {
    die("Invalid request.");
}

$appointment_id = $_GET['id'];
$status = $_GET['status'];

if (!in_array($status, ['approved', 'declined'])) {
    die("Invalid status.");
}

// Update appointment status
$query = "UPDATE appointments SET status = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $status, $appointment_id);

if ($stmt->execute()) {
    echo "<script>alert('Appointment status updated!'); window.location.href='owner_dashboard.php';</script>";
} else {
    die("<script>alert('Error updating status.'); window.history.back();</script>");
}

$stmt->close();
$conn->close();
?>
