<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="stylesheet" href="css/header_footer.css">
</head>
<body>
    
    <header>
        <nav class="navbar">
            <div class="logo"><img src="img/logo.png" alt="BrowsePG"></div>
            <ul class="nav-links">
                <li><a href="index.php">
                    <i class="fa-solid fa-house"></i>
                </a></li>
                <li><a href="about.php">
                    <i class="fa-solid fa-globe"></i>
                </a></li>
                <li><a href="dashboard.php">
                    <i class="fa-solid fa-table-columns"></i>
                </a></li>
                <li><a href="#" onclick="openpopup('signup-popup')">
                    <i class="fa-solid fa-user-plus"></i>
                </a></li>
                <li><a href="#" onclick="openpopup('login-popup')">
                    <i class="fa-solid fa-circle-user"></i>
                </a></li>
            </ul>

            <!-- SIGNUP MODAL -->
            <div id="signup-popup" class="popup">
                <div class="popup-content">
                    <span class="close" onclick="closepopup('signup-popup')">&times;</span>
                    <h2>SignUp with BrowsePG</h2>
                    
                    <!-- Signup Form -->
                    <form action="signdata.php" method="POST">

                    <i class="fa-solid fa-user"></i>
                    <label for="name">
                        <input type="text" name="name" placeholder="Full Name" id="name" required><br><br>
                    </label>

                    <i class="fa-solid fa-phone"></i>
                    <label for="phone">
                        <input type="text" name="phone" placeholder="Phone(10 digit)" id="phone" required><br><br>
                    </label>

                    <i class="fa-solid fa-envelope"></i>
                    <label for="email">
                        <input type="email" name="email" placeholder="Email" id="email" required><br><br>
                    </label>

                    <i class="fa-solid fa-lock"></i>
                    <label for="password">
                        <input type="password" name="password" placeholder="Password" id="password"><br><br>
                    </label>

                    <label for="gender"><b>Gender</b></label><br>

                    <input type="radio" class="gender" id="male" name="gender" value="male" required>
                    <label for="male">Male</label>

                    <input type="radio" class="gender" id="female" name="gender" value="female">
                    <label for="female">Female</label>

                    <input type="radio" class="gender" id="other" name="gender" value="other">
                    <label for="other">Other</label><br><br>

                    <label for="user_type"><b>I am a:</b></label><br>
                    <input class="user_type" type="radio" id="tenant" name="user_type" value="tenant" required>
                    <label for="tenant">Tenant</label><br>

                    <input class="user_type" type="radio" id="owner" name="user_type" value="owner">
                    <label for="owner">Owner</label><br><br>

                    <button  class="btn"  type="submit" name="signup">SignUp</button><br><br>

                </form>
            </div>
        </div>

            <!-- LOGIN MODAL -->
            <div id="login-popup" class="popup">
                <div class="popup-content">
                    <span class="close" onclick="closepopup('login-popup')">&times;</span>
                    <h2>Login with BrowsePG</h2>
                    <form action="logindata.php" method="post">
                        <i class="fa-solid fa-user"></i>
                        <label for="name"></label>
                        <input type="text" id="name" name="name" placeholder="Full Name"><br><br>

                        <i class="fa-solid fa-envelope"></i>
                        <label for="email">
                            <input type="email" name="email" placeholder="Email" id="email" required><br><br>
                        </label>

                        <i class="fa-solid fa-lock"></i>
                        <label for="password"></label>
                        <input type="password" id="password" name="password" placeholder="Password" required><br><br>

                        <label for="user_type"><b>I am a:</b></label><br>
                        <input class="user_type" type="radio" id="tenant" name="user_type" value="tenant" required>
                        <label for="tenant">Tenant</label><br>

                        <input class="user_type" type="radio" id="owner" name="user_type" value="owner">
                        <label for="owner">Owner</label><br><br>

                        <button class="btn" type="submit" name="login">Login</button><br><br>

                    </form>
                </div>
            </div>
        </nav>
        
    </header>

    <!-- popup  -->
    <script src="js/signup_login.js"></script> 
    </body>
</html>