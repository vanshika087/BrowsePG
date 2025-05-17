<?php
// Include database connection
include 'config.php';

// Get PG ID from URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM pg_listings WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $pg = mysqli_fetch_assoc($result);
} else {
    echo "Invalid request!";
    exit;
}
// Fetch amenities
$amenities_query = "SELECT amenities.name FROM amenities
                    JOIN pg_amenities ON amenities.id = pg_amenities.amenity_id
                    WHERE pg_amenities.pg_id = $id";
$amenities_result = mysqli_query($conn, $amenities_query);

$amenities = [];
while ($row = mysqli_fetch_assoc($amenities_result)) {
    $amenities[] = $row['name'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pg['name']; ?> - PG Details</title>
    <link rel="stylesheet" href="css/pg_details.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
</head>
<body>
    <div class="container">
        <img src="<?php echo $pg['image']; ?>" alt="PG Image" class="pg-image">
        <div class="details">
            <h1><?php echo $pg['name']; ?></h1>
            <p><strong>Location:</strong> <?php echo $pg['location']; ?></p>
            <p><strong>Price:</strong> ₹<?php echo $pg['price']; ?></p>
            <p><strong>Gender:</strong> <?php echo $pg['gender']; ?></p>
            <p><strong>Address:</strong> <?php echo $pg['address']; ?></p>
        </div>
        <!-- HTML for Displaying Amenities -->
        <div class="amenities">
            <h2>Amenities</h2>
            <ul>
                <?php 
                // Mapping amenities to FontAwesome icons
                $amenity_icons = [
                    "AC" => "fa-solid fa-wind",
                    "Bed" => "fa-solid fa-bed",
                    "CCTV" => "fa-solid fa-video",
                    "Dining" => "fa-solid fa-utensils",
                    "Fire Exit" => "fa-solid fa-door-open",
                    "Geyser" => "fa-solid fa-bath",
                    "Lift" => "fa-solid fa-elevator",
                    "Parking" => "fa-solid fa-car",
                    "Power Backup" => "fa-solid fa-bolt",
                    "RO Water" => "fa-solid fa-tint",
                    "TV" => "fa-solid fa-tv",
                    "Washing Machine" => "fa-solid fa-tshirt",
                    "WiFi" => "fa-solid fa-wifi"
                    ];
                    foreach ($amenities as $amenity) { 
                        $icon = isset($amenity_icons[$amenity]) ? $amenity_icons[$amenity] : "fa-solid fa-check"; 
                    ?>
                        <li><i class="<?php echo $icon; ?>"></i> <?php echo $amenity; ?></li>
                <?php } ?>
            </ul>
        </div>

        <div class="button-container">
            <button id="openAppointmentModal" class="btn btn-primary">Book Appointment</button>
            <button id="markInterested" class="btn btn-warning" data-pg-id="<?php echo $_GET['id']; ?>">Interested</button>
            <p id="interestedMessage" style="color: green; display: none;"></p>

        </div>

       <!-- Appointment Modal -->
        <div id="appointmentModal" class="modal">
            <div class="modal-content">
                <span class="close" id="closeAppointmentModal">&times;</span>
                <h2>Book an Appointment</h2>
                <form action="book_appointment.php" method="POST">
                    <!-- Hidden inputs for PG ID and User ID -->
                    <input type="hidden" id="pg_id" name="pg_id" value="<?php echo $_GET['id']; ?>">
                    <input type="hidden" id="user_id" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" id="date" name="date" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="time">Time</label>
                        <input type="time" id="time" name="time" class="form-control" required>
                    </div>

                    <button type="submit" id="appointmentForm" class="btn btn-success">Confirm Appointment</button>
                </form>
            </div>
        </div>
    </div>
    <script src="js/appointments.js"></script>
</body>
</html>