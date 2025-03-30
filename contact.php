<?php
include('includes/connect.php');

if (isset($_POST['submit_contact'])) {
  $customer_id = $_POST['customer_id'];
  $first_name = $_POST['first_name'];
  $last_name = $_POST['last_name'];
  $gender = $_POST['gender'];
  $email = $_POST['email'];
  $description = $_POST['description'];
  $age = $_POST['age']; // Add age field

  // Checking empty condition
  if (empty($customer_id) || empty($first_name) || empty($last_name) || empty($gender) || empty($email) || empty($description) || empty($age)) {
    echo "<script>alert('Please fill all the available fields');</script>";
    exit();
  } else {
    // Insert query
    $insert_contact = "INSERT INTO `contact` (customer_id, first_name, last_name, gender, email, description, date, age) VALUES ('$customer_id', '$first_name', '$last_name', '$gender', '$email', '$description', NOW(), '$age')";

    $result_query = mysqli_query($con, $insert_contact);

    if ($result_query) {
      echo "<script>alert('Contact submitted successfully');</script>";
    } else {
      echo "<script>alert('Contact submission failed: " . mysqli_error($con) . "');</script>";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Form</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">
  <div class="container mt-3">
    <h1 class="text-center">Contact Form</h1>
    <form action="" method="post">
      <div class="form-outline mb-4 w-50 m-auto">
        <label for="customer_id" class="form-label">Customer ID</label>
        <input type="text" name="customer_id" id="customer_id" class="form-control" placeholder="Enter customer ID" required>
      </div>
      <div class="form-outline mb-4 w-50 m-auto">
        <label for="first_name" class="form-label">First Name</label>
        <input type="text" name="first_name" id="first_name" class="form-control" placeholder="Enter first name" required>
      </div>
      <div class="form-outline mb-4 w-50 m-auto">
        <label for="last_name" class="form-label">Last Name</label>
        <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Enter last name" required>
      </div>
      <div class="form-outline mb-4 w-50 m-auto">
        <label class="form-label">Gender</label><br>
        <input type="radio" name="gender" id="gender_male" value="Male" required>
        <label for="gender_male">Male</label>
        <input type="radio" name="gender" id="gender_female" value="Female" required>
        <label for="gender_female">Female</label>
      </div>
      <div class="form-outline mb-4 w-50 m-auto">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" name="email" id="email" class="form-control" placeholder="Enter email address" required>
      </div>
      <div class="form-outline mb-4 w-50 m-auto">
        <label for="description" class="form-label">Description</label>
        <textarea name="description" id="description" class="form-control" placeholder="Enter your message" required></textarea>
      </div>
      <div class="form-outline mb-4 w-50 m-auto">
        <label for="age" class="form-label">Age</label>
        <input type="number" name="age" id="age" class="form-control" placeholder="Enter your age" required>
      </div>
      <div class="text-center mb-4">
        <button type="submit" name="submit_contact" class="btn btn-primary">Submit</button>
      </div>
    </form>
  </div>
</body>
</html>
