<?php
session_start();
header('Content-Type: application/json');
include '../includes/db.php';

$response = ["success" => false, "message" => "Unknown error."];

if (!isset($_SESSION['user_id'])) {
    $response['message'] = "Not logged in.";
    echo json_encode($response); exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_POST['new_status'])) {
    $seller_id = $_SESSION['user_id'];
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

$response['message'] = "Invalid request.";
echo json_encode($response);