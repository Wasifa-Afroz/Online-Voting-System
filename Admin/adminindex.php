<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

<div class="container-fluid p-0">
    <nav class="navbar navbar-expand-lg mt-0" style="background-color: #FFFAF0;">
        <div class="container-fluid">
            <img src="../images/image 2.png" alt="" style="width: 7%; height: auto;">
            <nav class="navbar navbar-expand-lg">
                <ul class="navbar-nav"></ul>
            </nav>
        </div>
    </nav>

    <div class="bg-light">
        <h3 class="text-center p-2">Manage Details</h3>
    </div>

    <div class="row">
        <div class="col-md-12 p-1 d-flex align-items-center" style="background-color: #FFE4E1;">
            <div class="text-center">
                <a href="#"><img src="../images/admin.png" alt="" style="width: 20%; height: auto;"></a>
                <p class="text-dark">Admin 1234</p>
            </div>
            <div class="button text-center">
                <button class="btn btn-info btn-md mx-2"><a href="insert_products.php" class="nav-link text-dark">Insert Products</a></button>
                <button class="btn btn-info btn-md mx-2"><a href="adminindex.php?view_products" class="nav-link text-dark">View Products</a></button>
                <button class="btn btn-info btn-md mx-2"><a href="adminindex.php?insert_category" class="nav-link text-dark">Insert Categories</a></button>
                <button class="btn btn-info btn-md mx-2"><a href="adminindex.php?view_categories" class="nav-link text-dark">View Categories</a></button>
                <button class="btn btn-info btn-md mx-2"><a href="adminindex.php?view_order" class="nav-link text-dark">All Orders</a></button>
                <button onclick="window.location.href='/Shopping Mart/Admin_login.php'" class="btn btn-info btn-md mx-2 text-dark">Logout</button>
            </div>
        </div>
    </div>
</div>

<!-- Access Form to insert category-->
<div class="container my-5">
    <?php
    if (isset($_GET['insert_category'])) {
        include('insert_categories.php');
    }
    ?>
</div>

<!-- Access Form to edit products-->
<div class="container my-5">
    <?php
    if (isset($_GET['view_products'])) {
        include('view_products.php'); // Ensure this file contains your product table code
    }
    ?>
</div>

<!-- Access Form to edit categories-->
<div class="container my-5">
    <?php
    if (isset($_GET['view_categories'])) {
        include('view_categories.php');
    }
    ?>
</div>

<!-- Access Form to edit order-->
<div class="container my-5">
    <?php
    if (isset($_GET['view_order'])) {
        include('view_order.php');
    }
    ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
