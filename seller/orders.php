
<?php
session_start();
header('Content-Type: application/json');
include '../includes/db.php';


$response = ["success" => false, "orders" => []];
if (!isset($_SESSION['user_id'])) {
    $response['message'] = "Not logged in.";
    echo json_encode($response); exit;
}
$seller_id = $_SESSION['user_id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_POST['new_status'])) {
    $order_id = intval($_POST['order_id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['new_status']);
    $allowed = ['pending','confirmed','delivered','cancelled'];
    if (!in_array($new_status, $allowed)) {
        $response['message'] = "Invalid status.";
        echo json_encode($response); exit;
    }
    
    $check = "SELECT COUNT(*) as cnt FROM order_items oi JOIN products p ON oi.product_id=p.product_id WHERE oi.order_id='$order_id' AND p.seller_id='$seller_id'";
    $res = mysqli_query($conn, $check);
    $row = mysqli_fetch_assoc($res);
    if ($row['cnt'] > 0) {
        $q = "UPDATE orders SET status='$new_status' WHERE order_id='$order_id'";
        if (mysqli_query($conn, $q)) {
            $response['success'] = true;
            $response['message'] = "Order status updated.";
        } else {
            $response['message'] = "Error updating status.";
        }
    } else {
        $response['message'] = "Unauthorized.";
    }
    echo json_encode($response); exit;
}

$query = "SELECT o.order_id, u.name AS customer, u.email, o.total_amount, o.order_date, o.status,
       oi.order_item_id, oi.quantity, oi.price, p.name AS product, p.category
     FROM orders o
     JOIN users u ON o.buyer_id = u.user_id
     JOIN order_items oi ON o.order_id = oi.order_id
     JOIN products p ON oi.product_id = p.product_id
     WHERE p.seller_id = '$seller_id'
     ORDER BY o.order_id DESC";
$result = mysqli_query($conn, $query);
$orders = [];
while ($row = mysqli_fetch_assoc($result)) {
    $orders[] = $row;
}
$response['success'] = true;
$response['orders'] = $orders;
echo json_encode($response);
