<?php
include('../includes/db.php');
header('Content-Type: application/json; charset=utf-8');

if (!isset($_POST['product_id'])) {
    echo json_encode(["success" => false, "message" => "Missing product ID"]);
    exit;
}

$product_id = intval($_POST['product_id']);

$stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
$stmt->bind_param("i", $product_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Product deleted successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to delete product"]);
}
?>
