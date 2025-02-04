<?php
require 'database.php'; // Include your database connection file
require 'partial/header.php';
session_start();

$db = new Database();
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$user = null;
if ($userId) {
    $user = $db->select("SELECT * FROM users WHERE id = ?", [$userId]);
    $imageUrl = $user[0]['profileUrl'];
    if ($user) {
        $user = $user[0]; // Assuming select returns an array of results
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $userId) {
    $firstname = $_POST['firstname'] ?? null;
    $lastname = $_POST['lastname'] ?? null;
    $username = $_POST['username'] ?? null;
    $email = $_POST['email'] ?? null;
    $phone = $_POST['phone'] ?? null;

    // Ensure no null values are passed into the query
    if ($firstname && $lastname && $username && $email && $phone) {
        $updateQuery = "UPDATE users SET firstname = ?, lastname = ?, username = ?, email = ?, phone = ? WHERE id = ?";
        $db->update($updateQuery, [$firstname, $lastname, $username, $email, $phone, $userId]);

        header("Location: dashboard.php"); // Redirect after updating
        exit;
    } else {
        echo "All fields are required.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        .container-box { min-width: 400px;display:flex; flex-direction: column; align-items: center; padding: 20px; border: 1px solid #ccc; border-radius: 10px; }
        img { width: 100px; height: 100px; border-radius: 50%; }
        input, button { width: 100%; margin: 10px 0; padding: 10px; }
         /* Avatar Container Styles */
    .avatar-container {
      width: 150px;
      height: 150px;
      margin: 0 auto 20px auto;
      position: relative;
      border-radius: 50%;
      overflow: hidden;
      border: 2px solid #ddd;
      background: #f1f1f1;
      cursor: pointer;
    }

    .avatar-container img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }

    /* Overlay button on avatar */
    .avatar-container .upload-overlay {
      position: absolute;
      bottom: 0;
      width: 100%;
      text-align: center;
      background: rgba(0, 0, 0, 0.5);
      color: #fff;
      font-size: 14px;
      padding: 5px 0;
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .avatar-container:hover .upload-overlay {
      opacity: 1;
    }

    /* Hide the actual file input */
    #avatarUpload {
      display: none;
    }
    button {
  background: #007bff; /* Maroon button */
  color: white;
  border: none;
  padding: 10px;
  border-radius: 5px;
  cursor: pointer;
  width: 85%;
  margin-top: 10px;
}

button:hover {
  background: #D9D9D9;
  color:black;
}    
    </style>
</head>
<body>
    
<nav class="navbar navbar-expand-lg navbar-light " style="background-color: #d9d9d9;">
        <div class="d-flex container">
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
<div style="background-color: #033047; color: white; min-height:85vh; display:flex; width:100%; align-items:center; justify-content:center ">
<div class="container-box" >
    <form action="" method="post" enctype="multipart/form-data" style="width:80%">
        <h2>Profile</h2>

        <div class="avatar-container" onclick="document.getElementById('avatarUpload').click();">
        <!-- Default avatar image (change the source if needed) -->
        <img id="avatarImage" src="<?php echo $user['profileUrl']; ?>" alt="Avatar" />
        <div class="upload-overlay">Upload Photo</div>
      </div>
      <!-- Hidden file input -->
      <input type="file" id="avatarUpload" name="photo" accept="image/*" onchange="previewAvatar(event)" />
    
            <input type="text" name="firstname" value="<?php echo $user['firstname']; ?>" placeholder="First Name" required><br>
            <input type="text" name="lastname" value="<?php echo $user['lastname']; ?>" placeholder="Last Name" required><br>
            <input type="text" name="username" value="<?php echo $user['username']; ?>" placeholder="Username" required><br>
            <input type="email" name="email" value="<?php echo $user['email']; ?>" placeholder="Email" required><br>
            <input type="number" name="phone" value="<?php echo $user['phone']; ?>" placeholder="Phone Number" required><br>
            <button type="submit" >Update Profile</button>
        </form>
    </div>
</div>
<script>
    function previewAvatar(event) {
      const input = event.target;
      const avatarImage = document.getElementById('avatarImage');
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          avatarImage.src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
      }
    }
  </script>
</body>
</html>
