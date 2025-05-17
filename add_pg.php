<?php
session_start();
include 'config.php'; // Ensure config.php has the database connection

// Ensure the user is logged in and is an owner
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'owner') {
    header("Location: index.php");
    exit();
}

$owner_id = $_SESSION['user_id'];

// Fetch owner's name from the database
$query = "SELECT name FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Error in query preparation (Fetching Owner Name): " . $conn->error);
}
$stmt->bind_param("i", $owner_id);
$stmt->execute();
$result = $stmt->get_result();
$owner = $result->fetch_assoc();
$stmt->close();

if (!$owner) {
    echo "<script>alert('Owner not found! Please log in again.'); window.location.href='logout.php';</script>";
    exit();
}

$owner_name = $owner['name'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $location = trim($_POST['location']);
    $price = trim($_POST['price']);
    $gender = trim($_POST['gender']);
    $address = trim($_POST['address']);

    // Image Upload Handling
    $targetDir = "uploads/";
    $fileName = basename($_FILES["image"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $uploadOk = 1;

    if (!empty($_FILES["image"]["name"])) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            // File uploaded successfully
        } else {
            echo "<script>alert('Error uploading image. Please try again.');</script>";
            $uploadOk = 0;
        }
    } else {
        echo "<script>alert('No file selected.');</script>";
        $uploadOk = 0;
    }

    // Insert Data into Database only if image upload is successful
    if ($uploadOk) {
        $query = "INSERT INTO pg_listings (owner_id, owner_name, name, location, price, gender, address, image) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($query);
        
        // Debug: Check if prepare() failed
        if (!$stmt) {
            die("Error in query preparation: " . $conn->error);
        }
        
        $stmt->bind_param("isssisss", $owner_id, $owner_name, $name, $location, $price, $gender, $address, $targetFilePath);
        
        if ($stmt->execute()) {
            echo "<script>alert('PG Added Successfully'); window.location.href='dashboard.php';</script>";
        } else {
            die("Error executing query: " . $stmt->error);
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add PG Listing</title>
    <link rel="stylesheet" href="css/add_pg.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <div class="container">
            <h2>Add New PG Listing</h2>
            <form action="add_pg.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="owner_id" value="<?php echo $owner_id; ?>">
                <input type="hidden" name="owner_name" value="<?php echo htmlspecialchars($owner_name); ?>">

                <input type="text" class="add_input" name="name" placeholder="PG Name" required><br>
                <input type="text" class="add_input" name="location" placeholder="Location" required><br>
                <input type="number" class="add_input" name="price" placeholder="Price (₹)" required><br>
                
                <select name="gender" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Unisex">Unisex</option>
                </select><br>

                <textarea name="address" placeholder="Address" required></textarea><br>
                <input type="file" name="image" required><br>
                <button type="submit">Add PG</button>
            </form>
        </div>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
