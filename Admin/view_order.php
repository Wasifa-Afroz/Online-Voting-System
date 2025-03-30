<?php
include('../includes/connect.php');

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $orderId = intval($_POST['delete']);

    // Prepare and execute the delete query using prepared statements to prevent SQL injection
    $stmt = mysqli_prepare($con, "DELETE FROM orders WHERE order_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $orderId);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        // Order deleted successfully, redirect to the same page
        echo "<script>alert('Order deleted successfully.');</script>";
        echo "<script>window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
        exit();
    } else {
        // Error handling: Order not found or deletion failed
        echo "<script>alert('Error deleting order.');</script>";
    }

    mysqli_stmt_close($stmt);
}

// Handle update request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $orderId = intval($_POST['order_id']);
    $customerId = intval($_POST['customer_id']);
    $productId = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    $orderDate = mysqli_real_escape_string($con, $_POST['order_date']);
    $totalPrice = floatval($_POST['total_price']);

    $sqlUpdate = "UPDATE orders SET 
                    customer_id = '$customerId',
                    product_id = '$productId',
                    quantity = '$quantity',
                    order_date = '$orderDate',
                    total_price = '$totalPrice'
                WHERE order_id = $orderId";

    mysqli_query($con, $sqlUpdate);
    header("Location: " . $_SERVER['PHP_SELF']); // Redirect to the same page to avoid resubmission
    exit();
}

// Fetch orders
$sql = "SELECT * FROM orders";
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
    <title>Order Table</title>
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
        <h1 class="mb-4">Order Table</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer ID</th>
                    <th>Product ID</th>
                    <th>Quantity</th>
                    <th>Order Date</th>
                    <th>Total Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($rows)): ?>
                    <?php foreach ($rows as $row): ?>
                        <tr data-id="<?php echo $row['order_id']; ?>">
                            <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['customer_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['product_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($row['order_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['total_price']); ?></td>
                            <td>
                                <button class="btn edit-btn" onclick="editOrder(<?php echo $row['order_id']; ?>)">Edit</button>
                                <button class="btn delete-btn" onclick="confirmDelete(<?php echo $row['order_id']; ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">No orders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        function confirmDelete(orderId) {
            if (confirm("Are you sure you want to delete this order?")) {
                const deleteForm = document.createElement('form');
                deleteForm.method = 'POST';
                deleteForm.action = ''; // Submit to the current page

                const deleteInput = document.createElement('input');
                deleteInput.type = 'hidden';
                deleteInput.name = 'delete';
                deleteInput.value = orderId;

                deleteForm.appendChild(deleteInput);
                document.body.appendChild(deleteForm);
                deleteForm.submit(); // Submit the form to trigger deletion
            }
        }

        function editOrder(orderId) {
            const row = document.querySelector('tr[data-id="'+orderId+'"]');
            const cells = row.getElementsByTagName('td');
            document.getElementById('editOrderId').value = orderId;
            document.getElementById('editCustomerId').value = cells[1].innerText;
            document.getElementById('editProductId').value = cells[2].innerText;
            document.getElementById('editQuantity').value = cells[3].innerText;
            document.getElementById('editOrderDate').value = cells[4].innerText;
            document.getElementById('editTotalPrice').value = cells[5].innerText;
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
            <h2>Edit Order</h2>
            <form method="POST" action="">
                <input type="hidden" id="editOrderId" name="order_id">
                <div>
                    <label for="editCustomerId">Customer ID:</label>
                    <input type="text" id="editCustomerId" name="customer_id" required>
                </div>
                <div>
                    <label for="editProductId">Product ID:</label>
                    <input type="text" id="editProductId" name="product_id" required>
                </div>
                <div>
                    <label for="editQuantity">Quantity:</label>
                    <input type="text" id="editQuantity" name="quantity" required>
                </div>
                <div>
                    <label for="editOrderDate">Order Date:</label>
                    <input type="text" id="editOrderDate" name="order_date" required>
                </div>
                <div>
                    <label for="editTotalPrice">Total Price:</label>
                    <input type="text" id="editTotalPrice" name="total_price" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Order</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
