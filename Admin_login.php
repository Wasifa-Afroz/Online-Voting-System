<?php
include('includes/connect.php');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_id = $_POST['admin_id'];
    $admin_password = $_POST['admin_password'];

    // Escape user inputs for security
    $admin_id = mysqli_real_escape_string($con, $admin_id);
    $admin_password = mysqli_real_escape_string($con, $admin_password);

    // Query the database to check if the credentials are valid
    $query = "SELECT * FROM Admin WHERE admin_id='$admin_id' AND admin_password='$admin_password'";
    $result = mysqli_query($con, $query);

    if (!$result) {
        die('Query Failed: ' . mysqli_error($con));
    }

    if (mysqli_num_rows($result) == 1) {
        // Login successful, redirect to the admin dashboard or desired page
        header('Location: Admin/adminIndex.php');
        exit();
    } else {
        // Set error message to display on the page
        $error_message = "Incorrect password or admin name.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
        }
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            background-color: #FFFAF0;
        }
        .main-container {
            display: flex;
            height: 100vh;
            padding-top: 56px; /* Adjust for navbar height */
        }
        .image-container {
            flex: 1;
            background-image: url("./Images/admin_login.jpg");
            background-size: cover;
            background-position: center;
        }
        .login-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .login-container form {
            width: 300px;
        }
        .error-message {
            color: red;
            font-size: 1rem;
            margin-top: 10px;
            text-align: center;
        }
        input[type="text"], input[type="password"] {
            font-size: 1.25rem;
            padding: 10px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="./Images/image 2.png" alt="Logo" style="width: 50px; height: auto;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php" style="font-size: 20px;">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php" style="font-size: 20px;">Product</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php" style="font-size: 20px;">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <div class="main-container">
        <div class="image-container"></div>
        <div class="login-container">
            <form action="" method="post">
                <h1>Admin Login</h1>
                
                <!-- Display error message if credentials are incorrect -->
                <?php if ($error_message): ?>
                    <p class="error-message"><?php echo $error_message; ?></p>
                <?php endif; ?>

                <label for="admin_id">Admin ID:</label>
                <input type="text" id="admin_id" name="admin_id" required class="form-control">

                <label for="admin_password">Password:</label>
                <input type="password" id="admin_password" name="admin_password" required class="form-control">

                <div class="form-outline mb-4 w-50 m-auto">
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
