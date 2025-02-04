<?php
require 'database.php'; // Include your database connection file
require 'partial/header.php';
session_start();

$db = new Database();
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$user = null;
if ($userId) {
    $user = $db->select("SELECT * FROM users WHERE id = ?", [$userId]);
    if ($user) {
        $user = $user[0]; 
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light " style="background-color: #d9d9d9;">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php" style="font-size:30px">Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto" style="font-size:20px"  >
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php">Product</a></li>
                    <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Main Content -->
    <div  style="min-height:85vh; display:flex; width:100%; align-items:center; justify-content:center;background-color: #033047; color: white; ">
        <h1 style="font-size: 72px !important;font-family: 'Roboto', sans-serif">Welcome to my website  <?php echo $user['username'] ?></h1>
    </div>
    <footer class="text-center py-3" style="background-color: #d9d9d9; color: black;">
        <p>&copy; Profile management. All rights reserved.</p>
    </footer>

</body>
</html>