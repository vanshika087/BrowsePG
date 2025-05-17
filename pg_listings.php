<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PG Listings</title>
    <link rel="stylesheet" href="css/pg_listings.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <div class="container">
        <h2>PG Listings <?php echo (!empty($city)) ? "in $city" : ""; ?></h2>

            <!-- Filter Section -->
            <div class="filter-container">
                <select id="locationFilter">
                    <option value="">Select Location</option>
                    <?php
                    include 'config.php';
                    $locQuery = "SELECT DISTINCT location FROM pg_listings";
                    $locResult = mysqli_query($conn, $locQuery);
                    while ($locRow = mysqli_fetch_assoc($locResult)) {
                        echo "<option value='{$locRow['location']}'>{$locRow['location']}</option>";
                    }
                    ?>
                </select>

                <select id="genderFilter">
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Unisex">Unisex</option>
                </select>

                <input type="number" id="minPrice" placeholder="Min Price">
                <input type="number" id="maxPrice" placeholder="Max Price">

                <button onclick="applyFilters()">Apply Filters</button>
            </div>
        

            <!-- PG Listings -->
            <div class="pg-listings">
            <?php
            $city = isset($_GET['city']) ? mysqli_real_escape_string($conn, $_GET['city']) : '';

            if (!empty($city)) {
                $query = "SELECT * FROM pg_listings WHERE location LIKE '%$city%'";
            } else {
                $query = "SELECT * FROM pg_listings";
            }
            
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="pg-card" data-location="<?php echo $row['location']; ?>" 
                    data-gender="<?php echo $row['gender']; ?>" 
                    data-price="<?php echo $row['price']; ?>">
                    <img src="<?php echo $row['image']; ?>" alt="PG Image">
                    <div class="info-container">
                        <h3><?php echo $row['name']; ?></h3>
                        <p>Location: <?php echo $row['location']; ?></p>
                        <p>Price: ₹<?php echo $row['price']; ?></p>
                        <p>Gender: <?php echo $row['gender']; ?></p>
                        <p><?php echo $row['address']; ?></p>
                        <a href="pg_details.php?id=<?php echo $row['id']; ?>" class="view-details">View Details</a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</main>
    <?php include 'footer.php'; ?>
    <script src="js/filter.js"></script> <!-- External JavaScript -->
</body>
</html>