<?php
require 'database.php'; 
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
    <title>Welcome  <?php echo $user['firstname'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
    
    <div >
    <div style="min-height:85vh; display:flex; flex-direction:column; width:100%;  justify-content:center;background-color: #033047; color: white; ">
    <h1 class="container d-flex justify-content-center" style="font-size: 60px !important;font-family: 'Roboto', sans-serif;">Product Management</h1>
    <div class="d-flex justify-content-center align-items-center" style="margin-bottom: 20px; ">
        <!-- <h2 class="mr-3">Product List</h2> -->
        <a href="create-product.php" class="btn btn-lg" style="background-color: #d9d9d9; color: black;">Add Product</a>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>S.N.</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                /* The line `select("SELECT * FROM products");` is fetching all
                records from the "products" table. The `select` method is defined in the Database class ("database.php"). */
                $db = new Database();
                $products = $db->select("SELECT * FROM products");
                $i=1; // Initialize the counter variable for the serial number
                foreach ($products as $p): ?>
                    <tr>
                        <td><?= $i++; ?></td> <!-- Increment the counter variable -->
                        <td><?= $p['title']; ?></td>
                        <td><?= $p['description']; ?></td> <!-- Access the product name from the $product array -->
                        <td><?= $p['price']; ?></td> <!-- Access the product price from the $product array -->
                        <td>
                            <!-- The `<a>` tag is creating a hyperlink that directs the user to the "edit-product.php" page with a specific
                            product ID appended as a query parameter in the URL. We can access such input by using $_GET['id'] in the "edit-product.php" page. -->
                            <a href="edit-product.php?id=<?= $p['id']; ?>" style="background-color: #033047; color: white;" class="btn btn-sm">Update</a>


                            <!-- The provided HTML form is used to delete a product entry from the
                            database. Here's a breakdown of what each part of the form does:
                            - The form is submitted via the POST method.
                            - The form action is set to "delete-product.php", which is the file that handles the deletion logic.
                            - An input field with the name "product_id" (hidden input) is used to store the ID of the product to be deleted.
                            - A button with the class "btn btn-danger btn-sm" is used to submit the form and trigger the deletion process.
                            -->
                            <form method="POST" action="delete-product.php" style="display:inline;">
                                <input type="hidden" name="product_id" value="<?= $p['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>
    <footer class="text-center py-3" style="background-color: #d9d9d9; color: black;">
        <p>&copy; Profile management. All rights reserved.</p>
    </footer>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

