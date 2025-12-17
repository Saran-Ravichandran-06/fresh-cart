
<?php
session_start();
header('Content-Type: application/json');
include '../includes/db.php';

$response = ["success" => false, "products" => []];
if (!isset($_SESSION['user_id'])) {
    $response['message'] = "Not logged in.";
    echo json_encode($response); exit;
}
$seller_id = $_SESSION['user_id'];
$query = "SELECT * FROM products WHERE seller_id='$seller_id'";
$result = mysqli_query($conn, $query);
$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}
$response['success'] = true;
$response['products'] = $products;
echo json_encode($response);
