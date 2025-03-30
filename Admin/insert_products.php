<?php
include('../includes/connect.php');

if (isset($_POST['insert_product'])) {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $product_keyword = $_POST['product_keyword'];
    $product_category = $_POST['product_category'];
    $product_price = $_POST['product_price'];
    $product_quantity = $_POST['product_quantity']; // New attribute for quantity
    $product_status = 'true';

    // Accessing images
    $product_image = $_FILES['product_image']['name'];
    // Accessing image tmp names
    $temp_image = $_FILES['product_image']['tmp_name'];

    // Checking empty condition
    if (empty($product_name) || empty($description) || empty($product_keyword) || empty($product_category) || empty($product_price) || empty($product_image) || empty($product_quantity)) {
        echo "<script>alert('Please fill all the available fields');</script>";
        exit();
    } else {
        move_uploaded_file($temp_image, "./product_img/$product_image");

        // Insert query
        $insert_products = "INSERT INTO `products` (product_name, product_description, product_keyword, product_image, category_ID, 
        product_price, product_quantity, date, status) VALUES ('$product_name', '$description', '$product_keyword', '$product_image', '$product_category', '$product_price', '$product_quantity', NOW(), '$product_status')";

        $result_query = mysqli_query($con, $insert_products);

        if ($result_query) {
            echo "<script>alert('Product inserted successfully');</script>";
        } else {
            echo "<script>alert('Product insertion failed: " . mysqli_error($con) . "');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Products</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">
    <div class="container mt-3">
        <h1 class="text-center">Insert Products</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <!-- Title -->
            <div class="form-outline mb-4 w-50 m-auto">
                <label for="product_name" class="form-label">Product Name</label>
                <input type="text" name="product_name" id="product_name" class="form-control" placeholder="Enter product name" autocomplete="off" required>
            </div>
            <!-- Description -->
            <div class="form-outline mb-4 w-50 m-auto">
                <label for="description" class="form-label">Description</label>
                <input type="text" name="description" id="description" class="form-control" placeholder="Description" autocomplete="off" required>
            </div>
            <!-- Product Keyword -->
            <div class="form-outline mb-4 w-50 m-auto">
                <label for="product_keyword" class="form-label">Product Keyword</label>
                <input type="text" name="product_keyword" id="product_keyword" class="form-control" placeholder="Enter keywords" autocomplete="off" required>
            </div>
            <!-- Categories -->
            <div class="form-outline mb-4 w-50 m-auto">
                <select name="product_category" class="form-select" required>
                    <option value="">Select a Category</option>
                    <?php
                    $select_query = "SELECT * FROM categories";
                    $result_query = mysqli_query($con, $select_query);
                    while ($row = mysqli_fetch_assoc($result_query)) {
                        $category_Name = $row['category_Name'];
                        $category_ID = $row['category_ID'];
                        echo "<option value='$category_ID'>$category_Name</option>";
                    }
                    ?>
                </select>
            </div>
            <!-- Image -->
            <div class="form-outline mb-4 w-50 m-auto">
                <label for="product_image" class="form-label">Product Image</label>
                <input type="file" name="product_image" id="product_image" class="form-control" required>
            </div>
            <!-- Price -->
            <div class="form-outline mb-4 w-50 m-auto">
                <label for="product_price" class="form-label">Product Price</label>
                <input type="text" name="product_price" id="product_price" class="form-control" placeholder="Enter price" autocomplete="off" required>
            </div>
            <!-- Quantity -->
            <div class="form-outline mb-4 w-50 m-auto">
                <label for="product_quantity" class="form-label">Product Quantity</label>
                <input type="number" name="product_quantity" id="product_quantity" class="form-control" placeholder="Enter quantity" required>
            </div>
            <!-- Insert Product -->
            <div class="form-outline mb-4 w-50 m-auto">
                <input type="submit" name="insert_product" class="btn btn-info mb-3 px-3" value="Insert Product">
            </div>
        </form>
    </div>
</body>
</html>
