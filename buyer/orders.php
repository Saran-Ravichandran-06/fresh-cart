
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete']) && isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);
    // Only allow deleting cancelled or delivered orders
    $check = "SELECT status FROM orders WHERE order_id='$order_id' AND buyer_id='$user_id'";
    $res = mysqli_query($conn, $check);
    $row = mysqli_fetch_assoc($res);
    if ($row && in_array($row['status'], ['cancelled', 'delivered'])) {
        mysqli_query($conn, "DELETE FROM order_items WHERE order_id='$order_id'");
        mysqli_query($conn, "DELETE FROM orders WHERE order_id='$order_id' AND buyer_id='$user_id'");
        $response['success'] = true;
        $response['message'] = "Order removed.";
    } else {
        $response['message'] = "Cannot remove an active order.";
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
