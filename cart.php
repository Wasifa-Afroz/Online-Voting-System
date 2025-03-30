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

<style>
.cart_img {
    width: 80px;
    height: 80px;
    object-fit: contain;
}

.d-flex {
  display: flex; /* Makes the container a flexible row */
  align-items: center; /* Aligns child elements vertically */
}

.form-select {
  margin-right: 10px; /* Add spacing between select and card details */
}

#card_details {
  display: none; /* Already set in your code */
}

    </style>
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
                Cart <i class="fa-solid fa-cart-shopping"></i></a>
        </div>
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

<!-- Main Table area  -->

<div class="container">
    <div class="row">
        <table class="table table-bordered text-center">
        <form action="" method ="post">
            <thead>
                <tr>
                    <th>Product Title</th>
                    <th>Product Image</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Remove</th>
                    <th colspan="2">Operations</th>
                </tr>
            </thead>
            <tbody>
                <!-- php code to display dynamic data -->
                <?php
    $get_ip_add = getIPAddress();
    $total_price = 0;
    $cart_query = "SELECT * FROM cart_details WHERE ip_address='$get_ip_add'";
    $result = mysqli_query($con, $cart_query);

    while ($row = mysqli_fetch_array($result)) {
        $product_id = $row['product_id'];
        $product_quantity = $row['product_quantity'];  // Retrieve product quantity from the cart

        $select_products = "SELECT * FROM products WHERE product_id='$product_id'";
        $result_products = mysqli_query($con, $select_products);

        while ($row_product_price = mysqli_fetch_array($result_products)) {
            $price_table = $row_product_price['product_price'];
            $product_name = $row_product_price['product_name'];
            $product_image = $row_product_price['product_image'];

            // Calculate total for this product based on its quantity
            $total_price += $price_table * $product_quantity;

?>

    <tr>
        <td><?php echo $product_name ?></td>
        <td><img src="./Images/<?php echo $product_image ?>" alt="" class="cart_img"></td>
        <td><input type="text" name="qty[<?php echo $product_id; ?>]" value="<?php echo $product_quantity; ?>" class="form-control w-50"></td>
        <td>$<?php echo $price_table ?></td>
        <td><input type="checkbox" name="removeitem[]" value="<?php echo $product_id; ?>"></td>
        <td>
            <input type="submit" value="Update Cart" class="bg-info px-3 py-2 border-0 mx-3" name="update_cart">
            <input type="submit" value="Remove" class="bg-info px-3 py-2 border-0 mx-3" name="remove_cart">
        </td>
    </tr>

<?php
        }
    }
?>

</tbody>
</table>

<!-- Subtotal Section -->
<div class="d-flex mb-5">
    <h4 class="px-3">Subtotal: $<strong class="text-info"><?php echo $total_price; ?></strong></h4>
    <div class="button-container">
        <a href="index.php"><button class="bg-info px-3 py-2 border-0">Continue Shopping</button></a>
        <a href="check_out.php" class="bg-secondary p-3 py-2 border-0" style="text-decoration: none; color: white;">Checkout</a>
    </div>
</div>

</div>
</div>
</form>

<div class="d-flex mb-5">
  <select name="product_category" class="form-select" id="payment_option" required>
    <option value="">Select payment option</option>
    <option value="Cash">Cash</option>
    <option value="Card">(Online Payment)Card</option>
  </select>

  <div id="card_details" style="display:none;">
    <div class="form-group">
      <label for="account_number">Account Number:</label>
      <input type="text" class="form-control" name="account_number" required>
    </div>
    <div class="form-group">
      <label for="cvc_number">CVC Number:</label>
      <input type="text" class="form-control" name="cvc_number" required>
    </div>
  </div>
</div>

<script>
    // JavaScript to toggle card details based on selected payment option
    document.getElementById('payment_option').addEventListener('change', function() {
        var cardDetails = document.getElementById('card_details');
        if (this.value === 'Card') {
            cardDetails.style.display = 'block';
        } else {
            cardDetails.style.display = 'none';
        }
    });
</script>

<!-- Function to remove items from the cart -->
<?php
function remove_cart_item() {
    global $con;
    if (isset($_POST['remove_cart'])) {
        foreach ($_POST['removeitem'] as $remove_id) {
            $delete_query = "DELETE FROM cart_details WHERE product_id=$remove_id";
            $run_delete = mysqli_query($con, $delete_query);
            if ($run_delete) {
                echo "<script>window.open('cart.php', '_self')</script>";
            }
        }
    }
}

echo $remove_item = remove_cart_item();
?>

<!-- Function to update cart -->
<?php
if (isset($_POST['update_cart'])) {
    foreach ($_POST['qty'] as $product_id => $quantity) {
        $quantity = intval($quantity);  // Ensure the quantity is a valid integer
        if ($quantity > 0) {
            $update_cart = "UPDATE cart_details SET product_quantity = $quantity WHERE product_id = $product_id AND ip_address = '$get_ip_add'";
            $result_quantities = mysqli_query($con, $update_cart);
        }
    }
    echo "<script>window.open('cart.php', '_self')</script>";
}
?>


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
