
<?php
session_start();
header('Content-Type: application/json');
include '../includes/db.php';

$response = ["success" => false, "message" => "Unknown error."];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!isset($_SESSION['user_id'])) {
    $response['message'] = "Not logged in.";
    echo json_encode($response); exit;
  }
  $seller_id = $_SESSION['user_id'];
  $name  = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
  $category = mysqli_real_escape_string($conn, $_POST['category'] ?? '');
  $price = floatval($_POST['price'] ?? 0);
  $quantity = intval($_POST['quantity'] ?? 0);

  
  $errors = [];
  if ($price < 0) $errors[] = "Price cannot be negative.";
  if ($quantity < 0) $errors[] = "Quantity cannot be negative.";
  if (!in_array($category, ['fruit','vegetable'])) $errors[] = "Invalid category.";
  if (empty($name)) $errors[] = "Product name required.";

  if (count($errors) > 0) {
    $response['message'] = implode(' ', $errors);
  } else {
    $query = "INSERT INTO products (seller_id, name, category, price, quantity) 
          VALUES ('$seller_id', '$name', '$category', '$price', '$quantity')";
    if (mysqli_query($conn, $query)) {
      $response['success'] = true;
      $response['message'] = "Product added successfully!";
    } else {
      $response['message'] = "Error: " . mysqli_error($conn);
    }
  }
}
echo json_encode($response);
