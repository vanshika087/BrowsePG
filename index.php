<?php
include 'config.php'; //Includes the database connection
session_start();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>BrowsePG - Home</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
        <link rel="stylesheet" href="css/index.css"> <!--Includes CSS file -->
    </head>
    <body>
        <?php include 'header.php'; ?>

        <main>
            <div class="hero-section">
                <div class="hero-text">Happiness Per Square Foot</div>
                <div class="search-box">
                    <input class="searchbar" type="text" placeholder="Enter your cities to search for PGs">
                        <button id="hero-text-button" type="submit" name="search-city">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                </div>
            </div>
        </main>

        <div class="content">
            <h2>Major Cities</h2>
            <div class="cities">
                <div class="city-delhi"><img src="img/delhi.png" alt="Delhi"></div>
                <div class="city-mumbai"><img src="img/mumbai.png" alt=""></div>
                <div class="city-hyderabad"><img src="img/hyderabad.png" alt=""></div>
                <div class="city-bengaluru"><img src="img/bangalore.png" alt=""></div>
            </div>
        </div>

        <?php include 'footer.php'; ?>

        <script src="js/index.js"></script> <!-- includes javascript-->

    </body>
</html>