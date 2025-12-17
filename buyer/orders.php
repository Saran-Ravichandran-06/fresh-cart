
<?php
session_start();
header('Content-Type: application/json');
include '../includes/db.php';

$response = ["success" => false, "orders" => []];
if (!isset($_SESSION['user_id'])) {
    $response['message'] = "Not logged in.";
    echo json_encode($response); exit;
}
$user_id = $_SESSION['user_id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel']) && isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);
    $q = "UPDATE orders SET status='cancelled' WHERE order_id='$order_id' AND buyer_id='$user_id' AND status='pending'";
    if (mysqli_query($conn, $q)) {
        $response['success'] = true;
        $response['message'] = "Order cancelled.";
    } else {
        $response['message'] = "Error cancelling order.";
    }
    echo json_encode($response); exit;
}


$query = "SELECT order_id, total_amount, status FROM orders WHERE buyer_id='$user_id' ORDER BY order_id DESC";
$result = mysqli_query($conn, $query);
$orders = [];
while ($row = mysqli_fetch_assoc($result)) {
    $orders[] = [
        "order_id" => $row['order_id'],
        "total_amount" => $row['total_amount'],
        "status" => $row['status']
    ];
}
$response['success'] = true;
$response['orders'] = $orders;
echo json_encode($response);
