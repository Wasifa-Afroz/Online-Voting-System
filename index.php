<?php
include('includes/connect.php');
include('functions/common_function.php');

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
    <script>
        $(document).ready(function(){
            $('.add-to-cart').click(function(e){
                e.preventDefault();  // Prevent the default action of the link
                
                var productId = $(this).data('product-id'); // Get product ID from data attribute
                
                // Send AJAX request to add product to cart
                $.ajax({
                    url: '',  // URL to the current page (index.php)
                    type: 'POST',
                    data: { product_id: productId },  // Send the product ID
                    success: function(response) {
                        var result = JSON.parse(response); // Parse the JSON response
                        alert(result.message); // Display the message
                    },
                    error: function() {
                        alert('Error adding product to cart. Please try again.');
                    }
                });
            });
        });
    </script>
</head>
<body>

<!-- Title Of Page  -->
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
            <a class="nav-link" href="#" style="font-size: 20px;">Price:$ <?php total_cart_price() ?></a>
        </div>
    
        <form class="d-flex ms-2" action="index.php" method="get">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search_data">
            <button class="btn btn-outline-info" type="submit">Search</button>
        </form>
    </div>
</nav>

<?php
//Calling cart function
cart();
?>

<!-- Second child -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #FFE4E1;">
    <ul class="navbar-nav me-auto">
        <li class="nav-item">
            <a class="nav-link" href="Admin_login.php" style="color: black; font-size: 20px;">Admin Login</a>
        </li>
    </ul>
</nav>

<!-- Main Content Area -->
<div class="container-fluid mt-2">
    <div class="row">
        <!-- Product Display Area -->
        <div class="col-md-10">
            <div class="row">
                <?php
                // If search data is present, show the search results; otherwise, show all products
                if (isset($_GET['search_data']) && !empty(trim($_GET['search_data']))) {
                    search_product(); // Call the search function to display search results
                } else {
                    if (isset($_GET['category']) && !empty($_GET['category'])) {
                        $category_id = intval($_GET['category']);
                        get_unique_categories($category_id); // Pass category ID
                    } else {
                        getproducts(); // Show all products
//                         $ip = getIPAddress();  
// echo 'User Real IP Address - '.$ip;  
                    }
                }
                ?>
            </div>
        </div>

        <!-- Sidebar for Categories -->
        <div class="col-md-2 bg-secondary text-white" style="min-height: 100vh;">
            <div class="bg-info text-white p-2 text-center">
                <h5 class="pt-3">Category</h5>
            </div>
            <div class="category-list">
                <?php getcategory(); ?>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-dark text-white mt-4 pt-4 pb-3">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>About Us</h5>
                <p>Fresh Finds is your go-to shopping mart for quality products. Experience convenience and freshness with every purchase.</p>
            </div>
            <div class="col-md-4">
                <h5>Contact Us</h5>
                <p><i class="fa fa-map-marker-alt"></i> 123 Fresh Lane, Karachi, Pakistan</p>
                <p><i class="fa fa-phone"></i> +92 123 456 7890</p>
                <p><i class="fa fa-envelope"></i> support@freshfinds.com</p>
            </div>
        </div>
        <div class="text-center mt-3">
            <hr class="bg-light">
            <p class="mb-0">&copy; 2024 Fresh Finds. All Rights Reserved.</p>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
