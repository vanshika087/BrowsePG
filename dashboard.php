<?php
session_start();
include 'header.php';
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_type = strtolower($_SESSION['user_type'] ?? 'tenant'); // Ensure lowercase

// Fetch user details securely
$query = "SELECT name, email, phone, gender, user_type FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

// Fetch PG listings or appointments based on user type
$pg_listings = [];
$appointments = [];

if ($user_type === 'tenant') {
    $query = "SELECT pg_listings.id, pg_listings.name, pg_listings.location, pg_listings.price, pg_listings.image 
              FROM interested_pgs 
              JOIN pg_listings ON interested_pgs.pg_id = pg_listings.id 
              WHERE interested_pgs.user_id = ?";
} else {
    $query = "SELECT appointments.id, appointments.appointment_date, appointments.appointment_time, appointments.status, 
                     pg_listings.name AS pg_name, users.name AS user_name, users.phone, users.email 
              FROM appointments 
              JOIN pg_listings ON appointments.pg_id = pg_listings.id 
              JOIN users ON appointments.user_id = users.id 
              WHERE pg_listings.owner_id = ?";
}

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($user_type === 'tenant') {
    $pg_listings = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $appointments = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - PGLife</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <script>
        function toggleEditPopup() {
            let popup = document.getElementById("editProfilePopup");
            popup.style.display = (popup.style.display === "block") ? "none" : "block";
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="profile-container">
            <h2>My Profile</h2>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
            <p><strong>Gender:</strong> <?php echo htmlspecialchars($user['gender']); ?></p>
            <p><strong>Account Type:</strong> <?php echo ucfirst($user_type); ?></p>
            <button onclick="toggleEditPopup()">Edit Profile</button>
            <a href="logout.php"><button style="background-color: red; color: white;">Logout</button></a>
        </div>

        <!-- Edit Profile Popup -->
        <div id="editProfilePopup" class="popup-container" style="display: none;">
            <div class="popup-content">
                <span class="close" onclick="toggleEditPopup()">&times;</span>
                <h3>Edit Profile</h3>
                <form method="post" action="update_profile.php">
                    <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                    <select name="gender">
                        <option value="Male" <?php if ($user['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                        <option value="Female" <?php if ($user['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                    </select>
                    <button type="submit" name="update_profile">Save Changes</button>
                </form>
            </div>
        </div>
    </div>

    <div class="main-content">
        <?php if ($user_type === 'tenant') { ?>
            <h2>Interested PGs</h2>
            <div class="pg-listings">
                <?php if (!empty($pg_listings)) { ?>
                    <?php foreach ($pg_listings as $pg) { ?>
                        <div class="pg-card">
                            <img src="<?php echo htmlspecialchars($pg['image'] ?? 'default-pg.jpg'); ?>" alt="PG Image">
                            <h3><?php echo htmlspecialchars($pg['name']); ?></h3>
                            <p>📍 Location: <?php echo htmlspecialchars($pg['location']); ?></p>
                            <p>💰 Price: ₹<?php echo htmlspecialchars($pg['price']); ?></p>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <p>No interested PGs found.</p>
                <?php } ?>
            </div>
        <?php } else { ?>
            <h2>Owner Dashboard</h2>
            <a href="add_pg.php"><button style="background-color: green; color: white;">Add PG Listing</button></a>

            <h2>Booked Appointments</h2>
            <?php if (!empty($appointments)) { ?>
                <table border="1">
                    <tr>
                        <th>PG Name</th>
                        <th>User Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($appointments as $appointment) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($appointment['pg_name']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['user_name']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['phone']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['email']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['appointment_time']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['status']); ?></td>
                            <td>
                                <?php if ($appointment['status'] === 'pending') { ?>
                                    <form method="post" action="confirm_appointment.php">
                                        <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                                        <button type="submit" name="confirm" style="background-color: green; color: white;">Confirm</button>
                                    </form>
                                <?php } else { ?>
                                    <button disabled style="background-color: gray; color: white;">Confirmed</button>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } else { ?>
                <p>No appointments found.</p>
            <?php } ?>
        <?php } ?>
    </div>
</body>
</html>

<?php include 'footer.php'; ?>
