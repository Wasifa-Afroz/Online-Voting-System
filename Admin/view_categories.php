<?php
include('../includes/connect.php');

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $categoryId = intval($_POST['delete']);

    // Prepare and execute the delete query using prepared statements to prevent SQL injection
    $stmt = mysqli_prepare($con, "DELETE FROM categories WHERE category_ID = ?");
    mysqli_stmt_bind_param($stmt, "i", $categoryId);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        // Category deleted successfully, redirect to the same page
        echo "<script>alert('Category deleted successfully.');</script>";
        echo "<script>window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
        exit();
    } else {
        // Error handling: Category not found or deletion failed
        echo "<script>alert('Error deleting category.');</script>";
    }

    mysqli_stmt_close($stmt);
}

// Handle update request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_id'])) {
    $categoryId = intval($_POST['category_id']);
    $categoryName = mysqli_real_escape_string($con, $_POST['category_name']);

    $sqlUpdate = "UPDATE categories SET 
                    category_Name = '$categoryName'
                WHERE category_ID = $categoryId";

    mysqli_query($con, $sqlUpdate);
    header("Location: " . $_SERVER['PHP_SELF']); // Redirect to the same page to avoid resubmission
    exit();
}

// Fetch categories
$sql = "SELECT * FROM categories";
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
    <title>Category Table</title>
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
        <h1 class="mb-4">Category Table</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Category ID</th>
                    <th>Category Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($rows)): ?>
                    <?php foreach ($rows as $row): ?>
                        <tr data-id="<?php echo $row['category_ID']; ?>">
                            <td><?php echo htmlspecialchars($row['category_ID']); ?></td>
                            <td><?php echo htmlspecialchars($row['category_Name']); ?></td>
                            <td>
                                <button class="btn edit-btn" onclick="editCategory(<?php echo $row['category_ID']; ?>)">Edit</button>
                                <button class="btn delete-btn" onclick="confirmDelete(<?php echo $row['category_ID']; ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">No categories found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        function confirmDelete(categoryId) {
            if (confirm("Are you sure you want to delete this category?")) {
                const deleteForm = document.createElement('form');
                deleteForm.method = 'POST';
                deleteForm.action = ''; // Submit to the current page

                const deleteInput = document.createElement('input');
                deleteInput.type = 'hidden';
                deleteInput.name = 'delete';
                deleteInput.value = categoryId;

                deleteForm.appendChild(deleteInput);
                document.body.appendChild(deleteForm);
                deleteForm.submit(); // Submit the form to trigger deletion
            }
        }

        function editCategory(categoryId) {
            const row = document.querySelector('tr[data-id="'+categoryId+'"]');
            const cells = row.getElementsByTagName('td');
            document.getElementById('editCategoryId').value = categoryId;
            document.getElementById('editCategoryName').value = cells[1].innerText;
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
            <h2>Edit Category</h2>
            <form method="POST" action="">
                <input type="hidden" id="editCategoryId" name="category_id">
                <div>
                    <label for="editCategoryName">Category Name:</label>
                    <input type="text" id="editCategoryName" name="category_name" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Category</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
