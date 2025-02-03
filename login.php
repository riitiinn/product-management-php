<?php
include 'database.php'; // Ensure this file contains your PDO connection setup

$db = new Database();
$conn = $db->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use a prepared statement to prevent SQL injection
    $sql = "SELECT * FROM users WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Fetch the user data
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) { // Check if user exists
        if (password_verify($password, $row['password'])) {
          header("Location: index.php");
          exit();
            echo "Login successful.";
        } else {
            echo "Invalid credentials.";
        }
    } else {
        echo "No user found.";
    }
}

?>

<!-- login.html -->
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="./assets/css/style.css">
    <style>
    body {
  padding: 20px; 
  background-color: #033047; /* Maroon background */
  font-family: Arial, sans-serif;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  margin: 0;
}

.container {
  background: white;
  padding: 20px;
  width: 30%;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  text-align: center;
}

h2 {
  text-align: center;
  margin-bottom: 20px;
}

form {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 100%;
}

input {
  width: 80%;
  padding: 10px;
  margin: 10px 0;
  border: 1px solid #ccc;
  border-radius: 5px;
  text-align: center;
}

button {
  background: #033047; /* Maroon button */
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

p {
  margin-top: 15px;
}

a {
  color: #007bff;
  text-decoration: none;
}

a:hover {
  text-decoration: underline;
}


    </style>
</head>
<body> 
    <div class="container">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Signup</a></p>
    </div>
</body>
</html>
