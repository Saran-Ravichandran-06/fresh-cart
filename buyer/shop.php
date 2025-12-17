
<?php
session_start();
header('Content-Type: application/json');
include '../includes/db.php';

$response = ["success" => false, "products" => []];
$query = "SELECT * FROM products WHERE quantity > 0 ORDER BY name ASC";
$result = mysqli_query($conn, $query);
$products = [];
while ($row = mysqli_fetch_assoc($result)) {
  $products[] = [
    "product_id" => $row['product_id'],
    "name" => $row['name'],
    "price" => $row['price'],
    "quantity" => $row['quantity'],
    "category" => $row['category']
  ];
}
$response['success'] = true;
$response['products'] = $products;
echo json_encode($response);
