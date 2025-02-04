 <?php
include 'database.php'; 

$db = new Database();
$conn = $db->getConnection();

$error = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $username = trim($_POST['username']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $photoPath = null;
    $error = null;

 
    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
   
        $hashed_pass = password_hash($password, PASSWORD_DEFAULT);


        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $error = "Email already registered. Try logging in.";
        } else {

            // Handle file upload
              if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath   = $_FILES['photo']['tmp_name'];
                $fileName      = $_FILES['photo']['name'];
                $fileNameCmps  = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));

                $allowedfileExtensions = array('jpg', 'jpeg', 'png', 'gif');
                if (!in_array($fileExtension, $allowedfileExtensions)) {
                    $error = "Upload failed. Allowed file types: " . implode(', ', $allowedfileExtensions);
                } else {
                    // Use absolute path for directory
                    $uploadFileDir = __DIR__ . '/assets/images/';
                    
                    // Create directory if it doesn't exist
                    if (!is_dir($uploadFileDir)) {
                        if (!mkdir($uploadFileDir, 0755, true)) {
                            $error = "Failed to create upload directory.";
                        }
                    }

                    if (!$error) {
                        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                        $dest_path = $uploadFileDir . $newFileName;

                        if (move_uploaded_file($fileTmpPath, $dest_path)) {
                            $photoPath = 'assets/images/' . $newFileName;
                        } else {
                            $error = "Failed to move uploaded file. Check directory permissions. Temp: $fileTmpPath, Dest: $dest_path";
                            error_log($error);
                        }
                    }
                }
              } elseif (isset($_FILES['photo'])) {
                $error_code = $_FILES['photo']['error'];
                if ($error_code !== UPLOAD_ERR_NO_FILE) {
                    $error = "File upload error. Code: " . $error_code;
                    error_log($error);
                }
              }

       
            $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, username, email,phone, password, profileUrl) VALUES (?, ?, ?, ?, ?, ?,? )");
            if ($stmt->execute([$firstname, $lastname, $username, $email,$phone, $hashed_pass, $photoPath])) {
               
                header("Location: login.php?signup=success");
                exit();
            } else {
                $error = "Error: Could not complete signup.";
            }
        }
    }
}

// session_start();
// require_once './includes/db.php';
// require_once './includes/functions.php';

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $firstname = sanitizeInput($_POST['firstname']);
//     $lastname = sanitizeInput($_POST['lastname']);
//     $username = sanitizeInput($_POST['username']);
//     $email = sanitizeInput($_POST['email']);
//     $phone = sanitizeInput($_POST['phone']);
//     $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

//     $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, username, email,phone, password) VALUES (?, ?, ?, ?, ?, ?)");
//     if ($stmt->execute([$firstname, $lastname, $username, $email, $phone, $password])) {
//         $_SESSION['user_id'] = $pdo->lastInsertId();
//         redirect('index.php');
//     } else {
//         $error = "Registration failed. Please try again.";
//     }
// }
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Form</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #033047;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #033047;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #D9D9D9;
            color: rgb(0, 0, 0);
        }

        /* Error Message Styling */
        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
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
        
    </style>
</head>
<body>
    <div class="container">
        <h2>Signup</h2>

        <!-- Display Error Message if Exists -->
        <?php if (!empty($error)): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="register.php" method="POST" enctype="multipart/form-data">
            <div class="avatar-container" onclick="document.getElementById('avatarUpload').click();">
              <!-- Default avatar image (change the source if needed) -->
                <img id="avatarImage" src="assets/images/ava.png" alt="Avatar" />
                <div class="upload-overlay">Upload Photo</div>
            </div>
            <!-- Hidden file input -->
            <input type="file" id="avatarUpload" name="photo" accept="image/*" onchange="previewAvatar(event)" />
            <input type="text" name="firstname" placeholder="First Name" required>
            <input type="text" name="lastname" placeholder="Last Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="number" name="phone" placeholder="Phone" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit" name="register">Sign Up</button>
        </form>

        <p>Already have an account? <a href="login.php">Login</a></p>
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
