<?php
// Including connect files
include('./includes/connect.php');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);


// Function to get all products if no category is selected
function getproducts() {
    global $con;

    // Fetch all products if no category is selected
    $select_query = "SELECT * FROM products";
    $result_query = mysqli_query($con, $select_query);

    if (!$result_query || mysqli_num_rows($result_query) == 0) {
        echo "<h2 class='text-center text-danger'>No products available</h2>";
        return;
    }

    // Display each product
    while ($row = mysqli_fetch_assoc($result_query)) {
        display_product_card($row);
    }
}

// Function to get products by category if a category is selected
function get_unique_categories($category_id) {
    global $con;

    $category_id = intval($category_id); // Ensure it's an integer
    $select_query = "SELECT * FROM products WHERE category_ID = $category_id";
    $result_query = mysqli_query($con, $select_query);

    if (!$result_query || mysqli_num_rows($result_query) == 0) {
        echo "<h2 class='text-center text-danger'>No stock for this category</h2>";
        return;
    }

    while ($row = mysqli_fetch_assoc($result_query)) {
        display_product_card($row);
    }
}

// Helper function to display a product card
function display_product_card($row) {
    $product_id = $row['product_id'];
    $product_name = $row['product_name'];
    $product_description = $row['product_description'];
    $product_image = $row['product_image'];
    $product_price = $row['product_price'];

    echo "
        <div class='col-md-4 mb-2'>
            <div class='card'>
                <img src='./Admin/product_img/$product_image' class='card-img-top' alt='$product_name' style='width: 60%; height: auto;'>
                <div class='card-body'>
                    <h5 class='card-title'>$product_name</h5>
                    <p class='card-text'>$product_description</p>
                    <p class='card-text'><strong>Price:$</strong> $product_price</p>
                    <a href='index.php?add_to_cart=$product_id' class='btn btn-info'>Add to Cart</a>
                </div>
            </div>
        </div>";
}

// Function to search products by keyword
function search_product() {
    global $con;

    // Check if the search_data key exists in the GET request
    if (isset($_GET['search_data']) && !empty($_GET['search_data'])) {
        // Escape the user input to prevent SQL injection
        $search_data = mysqli_real_escape_string($con, $_GET['search_data']);
        
        // Prepare the SQL query using the search keyword
        $search_query = "SELECT * FROM products WHERE product_keyword LIKE '%$search_data%'";
        $result_query = mysqli_query($con, $search_query);

        // Check if there are any results
        if (!$result_query || mysqli_num_rows($result_query) == 0) {
            echo "<h2 class='text-center text-danger'>No products available for this keyword</h2>";
            return;
        }

        // Display each product
        while ($row = mysqli_fetch_assoc($result_query)) {
            display_product_card($row);
        }
    }
}

// Display the categories
function getcategory() {
    global $con;
    $select_categories = "SELECT * FROM categories";
    $result_categories = mysqli_query($con, $select_categories);

    while ($row_data = mysqli_fetch_assoc($result_categories)) {
        $category_title = $row_data['category_Name'];
        $category_id = $row_data['category_ID'];

        echo "<div class='category-item text-center'>";
        echo "<a href='index.php?category=$category_id' class='nav-link text-light'>$category_title</a>";
        echo "</div>";
    }
}

// Get IP address function
function getIPAddress() {  
    // whether ip is from the share internet  
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {  
        $ip = $_SERVER['HTTP_CLIENT_IP'];  
    }  
    // whether ip is from the proxy  
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
    }  
    // whether ip is from the remote address  
    else {  
        $ip = $_SERVER['REMOTE_ADDR'];  
    }  
    return $ip;  
}  

function cart() {
    if (isset($_GET['add_to_cart'])) {
        global $con;

        $get_ip_add = getIPAddress();
        $get_product_id = $_GET['add_to_cart'];
        $product_quantity = 1; // Default quantity

        // Check if the product is already in the cart
        $select_query = "SELECT * FROM cart_details WHERE ip_address='$get_ip_add' AND product_id='$get_product_id'";
        $result_query = mysqli_query($con, $select_query);

        if (!$result_query) {
            echo "<script>alert('Error checking cart: " . mysqli_error($con) . "')</script>";
            return;
        }

        $num_of_rows = mysqli_num_rows($result_query);
        if ($num_of_rows > 0) {
            echo "<script>alert('This item is already present inside cart')</script>";
            echo "<script>window.open('index.php', '_self')</script>";
        } else {
            // Insert the new product into the cart
            $insert_query = "INSERT INTO cart_details (product_id, ip_address, product_quantity) VALUES ('$get_product_id', '$get_ip_add', $product_quantity)";
            $result_insert = mysqli_query($con, $insert_query);

            if (!$result_insert) {
                echo "<script>alert('Error adding item to cart: " . mysqli_error($con) . "')</script>";
            } else {
                echo "<script>alert('Item added to cart successfully!')</script>";
            }

            echo "<script>window.open('index.php', '_self')</script>";
        }
    }
}
//Total Price calculation 
function total_cart_price() {
    global $con;
    $get_ip_add = getIPAddress();

    $total_price = 0;
    $cart_query = "SELECT * FROM cart_details WHERE ip_address='$get_ip_add'";
    $result = mysqli_query($con, $cart_query);

    while ($row = mysqli_fetch_array($result)) {
        $product_id = $row['product_id'];

        $select_products = "SELECT product_price FROM products WHERE product_id='$product_id'";
        $result_products = mysqli_query($con, $select_products);

        while ($row_product_price = mysqli_fetch_array($result_products)) {
            $product_price = $row_product_price['product_price']; 
            $total_price += $product_price;  // Add individual product price to total
        }
    }

    echo $total_price;
}


?>
