<?php
include('../includes/connect.php');

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $productId = intval($_POST['delete']);

    // Prepare and execute the delete query using prepared statements to prevent SQL injection
    $stmt = mysqli_prepare($con, "DELETE FROM products WHERE product_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $productId);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        // Product deleted successfully, redirect to the same page
        echo "<script>alert('Product deleted successfully.');</script>";
        echo "<script>window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
        exit();
    } else {
        // Error handling: Product not found or deletion failed
        echo "<script>alert('Error deleting product.');</script>";
    }

    mysqli_stmt_close($stmt);
}

// Handle update request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productId = intval($_POST['product_id']);
    $productName = mysqli_real_escape_string($con, $_POST['product_name']);
    $productDescription = mysqli_real_escape_string($con, $_POST['product_description']);
    $productKeyword = mysqli_real_escape_string($con, $_POST['product_keyword']);
    $categoryId = intval($_POST['category_id']);
    $productPrice = floatval($_POST['product_price']);
    $productQuantity = intval($_POST['product_quantity']);

    $sqlUpdate = "UPDATE products SET 
                    product_name = '$productName',
                    product_description = '$productDescription',
                    product_keyword = '$productKeyword',
                    category_ID = $categoryId,
                    product_price = $productPrice,
                    product_quantity = $productQuantity
                WHERE product_id = $productId";

    mysqli_query($con, $sqlUpdate);
    header("Location: " . $_SERVER['PHP_SELF']); // Redirect to the same page to avoid resubmission
    exit();
}

// Fetch products
$sql = "SELECT * FROM products";
$result = mysqli_query($con, $sql);
$rows = [];
while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Table</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .edit-btn {
            background-color: lightgreen;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
        .delete-btn {
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
        .table {
            width: 100%;
            font-size: 1rem;
        }
        .modal-content {
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Product Table</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Product Description</th>
                    <th>Product Keyword</th>
                    <th>Category ID</th>
                    <th>Product Price</th>
                    <th>Product Quantity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($rows)): ?>
                    <?php foreach ($rows as $row): ?>
                        <tr data-id="<?php echo $row['product_id']; ?>">
                            <td><?php echo htmlspecialchars($row['product_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['product_description']); ?></td>
                            <td><?php echo htmlspecialchars($row['product_keyword']); ?></td>
                            <td><?php echo htmlspecialchars($row['category_ID']); ?></td>
                            <td><?php echo htmlspecialchars($row['product_price']); ?></td>
                            <td><?php echo htmlspecialchars($row['product_quantity']); ?></td>
                            <td>
                                <button class="btn edit-btn" onclick="editProduct(<?php echo $row['product_id']; ?>)">Edit</button>
                                <button class="btn delete-btn" onclick="confirmDelete(<?php echo $row['product_id']; ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">No products found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        function confirmDelete(productId) {
            if (confirm("Are you sure you want to delete this product?")) {
                const deleteForm = document.createElement('form');
                deleteForm.method = 'POST';
                deleteForm.action = ''; // Submit to the current page

                const deleteInput = document.createElement('input');
                deleteInput.type = 'hidden';
                deleteInput.name = 'delete';
                deleteInput.value = productId;

                deleteForm.appendChild(deleteInput);
                document.body.appendChild(deleteForm);
                deleteForm.submit(); // Submit the form to trigger deletion
            }
        }

        function editProduct(productId) {
            const row = document.querySelector('tr[data-id="'+productId+'"]');
            const cells = row.getElementsByTagName('td');
            document.getElementById('editProductId').value = productId;
            document.getElementById('editProductName').value = cells[1].innerText;
            document.getElementById('editProductDescription').value = cells[2].innerText;
            document.getElementById('editProductKeyword').value = cells[3].innerText;
            document.getElementById('editCategoryId').value = cells[4].innerText;
            document.getElementById('editProductPrice').value = cells[5].innerText;
            document.getElementById('editProductQuantity').value = cells[6].innerText;
            document.getElementById('editModal').style.display = 'block'; // Show modal
        }
        
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none'; // Hide modal
        }
    </script>

    <!-- Edit Modal -->
    <div id="editModal" style="display:none;">
        <div class="modal-content">
            <span onclick="closeEditModal()" style="cursor:pointer;">&times;</span>
            <h2>Edit Product</h2>
            <form method="POST" action="">
                <input type="hidden" id="editProductId" name="product_id">
                <div>
                    <label for="editProductName">Product Name:</label>
                    <input type="text" id="editProductName" name="product_name" required>
                </div>
                <div>
                    <label for="editProductDescription">Product Description:</label>
                    <textarea id="editProductDescription" name="product_description" required></textarea>
                </div>
                <div>
                    <label for="editProductKeyword">Product Keyword:</label>
                    <input type="text" id="editProductKeyword" name="product_keyword" required>
                </div>
                <div>
                    <label for="editCategoryId">Category ID:</label>
                    <input type="number" id="editCategoryId" name="category_id" required>
                </div>
                <div>
                    <label for="editProductPrice">Product Price:</label>
                    <input type="number" step="0.01" id="editProductPrice" name="product_price" required>
                </div>
                <div>
                    <label for="editProductQuantity">Product Quantity:</label>
                    <input type="number" id="editProductQuantity" name="product_quantity" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Product</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
