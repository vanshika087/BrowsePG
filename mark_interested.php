<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit();
}

$user_id = $_SESSION['user_id'];
$pg_id = $_POST['pg_id'] ?? 0;

if ($pg_id) {
    // Check if already marked interested
    $check_query = "SELECT * FROM interested_pgs WHERE user_id = ? AND pg_id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ii", $user_id, $pg_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Insert into interested_pgs table
        $insert_query = "INSERT INTO interested_pgs (user_id, pg_id) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ii", $user_id, $pg_id);
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Marked as interested"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database error"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Already marked as interested"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid PG ID"]);
}
?>
