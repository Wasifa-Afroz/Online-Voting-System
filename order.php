<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['customer_id'])) {
    header('Location: login.php');
    exit;
}

$customer_id = $_SESSION['customer_id'];
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];
$email = $_SESSION['email'];
$address = $_SESSION['address'];

include('includes/connect.php');
include('functions/common_function.php');

// Function to clear the cart
function clear_cart($get_ip_add) {
    global $con;
    // Delete all items from the cart for the given IP address
    $clear_cart_query = "DELETE FROM cart_details WHERE ip_address='$get_ip_add'";
    mysqli_query($con, $clear_cart_query);
}

// Function to save order into the database
function save_order($customer_id, $product_id, $product_quantity, $total_price) {
    global $con;
    
    // Prepare the SQL query
    $order_query = "INSERT INTO `orders` (customer_id, product_id, quantity, total_price) 
                    VALUES (?, ?, ?, ?)";
    
    // Initialize a statement
    if ($stmt = mysqli_prepare($con, $order_query)) {
        // Bind parameters
        mysqli_stmt_bind_param($stmt, "iiid", $customer_id, $product_id, $product_quantity, $total_price);
        
        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            // Successful execution
            mysqli_stmt_close($stmt);
            return true;
        } else {
            // Execution failed
            mysqli_stmt_close($stmt);
            return "Error executing query: " . mysqli_error($con);
        }
    } else {
        // Statement preparation failed
        return "Error preparing statement: " . mysqli_error($con);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Mart</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>

<!-- Title of Page -->
<div class="bg-light">
    <h3 class="text-center">Fresh Finds</h3>
    <p class="text-center">"Welcome to FreshFinds—where quality meets convenience, and every find feels fresh and fulfilling."</p>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg mt-0" style="background-color: #FFFAF0;">
    <img src="./Images/image 2.png" alt="" style="width: 7%; height: auto;">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
            <a class="nav-link active" aria-current="page" href="index.php" style="font-size: 20px;">Home</a>
            <a class="nav-link" href="index.php" style="font-size: 20px;">Product</a>
            <a class="nav-link" href="contact.php" style="font-size: 20px;">Contact</a>
            <a class="nav-link ms-auto" href="cart.php" style="font-size: 20px;">
                Cart <i class="fa-solid fa-cart-shopping"></i>
            </a>
        </div>
    </div>
</nav>

<!-- Second child -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #FFE4E1;">
    <ul class="navbar-nav me-auto">
        <li class="nav-item">
            <a class="nav-link" href="Admin_login.php" style="color: black; font-size: 20px;">Admin Login</a>
        </li>
    </ul>
</nav>

<?php
// Display customer details
echo "<div class='container'>";
echo "<h2>Welcome, $first_name $last_name!</h2>";
echo "<p>Email: $email</p>";
echo "<p>Address: $address</p>";
echo "</div>";
?>

<!-- Logout Button -->
<div class="container mb-4">
    <form action="" method="post">
        <button type="submit" name="logout" class="btn btn-danger">Logout</button>
    </form>
</div>

<!-- Main Table area -->
<div class="container">
    <div class="row">
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Customer ID</th>
                    <th>Product Title</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_price = 0;
                $get_ip_add = getIPAddress();
                $cart_query = "SELECT * FROM cart_details WHERE ip_address='$get_ip_add'";
                $result = mysqli_query($con, $cart_query);
                $products_in_cart = false;

                while ($row = mysqli_fetch_array($result)) {
                    $product_id = $row['product_id'];
                    $product_quantity = $row['product_quantity'];

                    $select_products = "SELECT * FROM products WHERE product_id='$product_id'";
                    $result_products = mysqli_query($con, $select_products);

                    while ($row_product_price = mysqli_fetch_array($result_products)) {
                        $price_table = $row_product_price['product_price'];
                        $product_name = $row_product_price['product_name'];

                        // Calculate the total price for this product
                        $total_price += $price_table * $product_quantity;
                        $products_in_cart = true;
                ?>
                <tr>
                    <td><?php echo $customer_id; ?></td>
                    <td><?php echo $product_name; ?></td>
                    <td>
                        <input type="text" name="qty[<?php echo $product_id; ?>]" value="<?php echo $product_quantity; ?>" class="form-control w-50">
                    </td>
                </tr>
                <?php
                    }
                }

                // Show the total price if there are products in the cart
                if ($products_in_cart) {
                    echo "<tr><td colspan='2'><strong>Total Price: $</strong>" . $total_price . "</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Save Order Button -->
<div class="container mb-4">
    <form action="" method="post">
        <button type="submit" name="save_order" class="btn btn-success">Save Order</button>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Logout functionality
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout'])) {
    session_unset();  // Remove all session variables
    session_destroy();  // Destroy the session

    // Clear the cart from session
    unset($_SESSION['cart']);

    // Clear cart from the database
    $ip_address = getIPAddress();
    $clear_cart_query = "DELETE FROM cart_details WHERE ip_address='$ip_address'";
    mysqli_query($con, $clear_cart_query);  // Execute the query

    // Redirect to the homepage (index.php)
    header("Location: index.php");
    exit;
}

// Save order functionality
if (isset($_POST['save_order'])) {
    $total_price = 0; // Initialize total price
    $get_ip_add = getIPAddress();
    $cart_query = "SELECT * FROM cart_details WHERE ip_address='$get_ip_add'";
    $result = mysqli_query($con, $cart_query);

    while ($row = mysqli_fetch_array($result)) {
        $product_id = $row['product_id'];
        $product_quantity = $row['product_quantity'];

        $select_products = "SELECT * FROM products WHERE product_id='$product_id'";
        $result_products = mysqli_query($con, $select_products);

        while ($row_product_price = mysqli_fetch_array($result_products)) {
            $price_table = $row_product_price['product_price'];
            $product_name = $row_product_price['product_name'];

            // Calculate the total price for this product
            $total_price += $price_table * $product_quantity;

            // Save the order into the database
            save_order($customer_id, $product_id, $product_quantity, $total_price);
        }
    }

    // Display a success message after saving the order
    echo "<script>alert('Order has been placed successfully!');</script>";
}
?>
