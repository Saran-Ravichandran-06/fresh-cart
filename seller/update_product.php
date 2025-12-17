<?php
include('../includes/db.php');
header('Content-Type: application/json; charset=utf-8');

if (!isset($_POST['product_id'])) {
    echo json_encode(["success" => false, "message" => "Missing product ID"]);
    exit;
}

$product_id = intval($_POST['product_id']);
$name = trim($_POST['name']);
$category = trim($_POST['category']);
$price = floatval($_POST['price']);
$quantity = intval($_POST['quantity']);


if ($name == '' || $price < 0 || $quantity < 0) {
    echo json_encode(["success" => false, "message" => "Invalid product details"]);
    exit;
}


$stmt = $conn->prepare("UPDATE products SET name=?, category=?, price=?, quantity=? WHERE product_id=?");
$stmt->bind_param("ssdii", $name, $category, $price, $quantity, $product_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Product updated successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to update product"]);
}
?>
