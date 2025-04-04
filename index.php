<?php 

require 'login.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <!-- Include Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<form action="" method="post">
        <div class="title">
            <div class="logo-image">
                <img src="image/logo.png" alt="Profile Image">
            </div>
            <div>
            <h3 class="text-center">
                DIGITAL MANAGEMENT INFORMATION SYSTEM OF BARANGAY MAMATID CABUYAO LAGUNA
            </h3>
            </div>
           
        </div>
        
        <h2>Login</h2>
        <?php
            if(isset($_GET['error'])) {
                echo "<p class='error'>Invalid username or password!</p>";
            }
        ?>
        
        <div class="input-container">
            <label for="username"><i class="fas fa-user"></i></label> 
            <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="input-container">
            <label for="password"><i class="fas fa-lock"></i></label> 
            <input type="password" name="password" placeholder="Password" required>
        </div>

        <button type="submit" name="login">Login</button>
        <p>Don't have an account? <a href="register.php">Register</a></p>
        </form>
        </div>
</body>
</html>


