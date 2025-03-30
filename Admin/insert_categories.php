<?php
include('../includes/connect.php');

if (isset($_POST['insert_cat'])) {
    $category_Name = $_POST['cat_name'];

    // Check if category already exists
    $select_query = "SELECT * FROM categories WHERE category_Name=?";
    $stmt = mysqli_prepare($con, $select_query);
    mysqli_stmt_bind_param($stmt, "s", $category_Name);
    mysqli_stmt_execute($stmt);
    $result_select = mysqli_stmt_get_result($stmt);

    $number = mysqli_num_rows($result_select);

    if ($number > 0) {
        echo "<script>alert('This category is already present in the database')</script>";
    } else {
        // Insert the category if it doesn't exist
        $insert_query = "INSERT INTO categories (category_Name) VALUES (?)";
        $stmt = mysqli_prepare($con, $insert_query);
        mysqli_stmt_bind_param($stmt, "s", $category_Name);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "<script>alert('Category has been inserted successfully')</script>";
        } else {
            echo "<script>alert('Error inserting category')</script>";
        }
    }
    mysqli_stmt_close($stmt);
}
?>
<h2 class="text-center">Insert Categories</h2>
<form action="" method="post" class="mb-2">
    <div class="input-group w-90 mb-2">
        <span class="input-group-text bg-info" id="basic-addon1"><i class="fa-solid fa-receipt"></i></span>
        <input type="text" class="form-control" name="cat_name" placeholder="Insert categories" aria-label="Username" aria-describedby="basic-addon1">
    </div>
    <div class="input-group w-10 mb-2 m-auto">
    <input type="submit" class="bg-info border-0 p-2 my-3" name="insert_cat" value="Insert Categories">
</div>
</form>