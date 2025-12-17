
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
    if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
        $response['message'] = "Cart is empty.";
        echo json_encode($response); exit;
    }
    $user_id = $_SESSION['user_id'];
    $name    = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
    $address = mysqli_real_escape_string($conn, $_POST['address'] ?? '');
    $payment = $_POST['payment'] ?? '';
    $total   = 0;

    foreach ($_SESSION['cart'] as $id => $qty) {
        $q = "SELECT price FROM products WHERE product_id='$id'";
        $r = mysqli_query($conn, $q);
        $row = mysqli_fetch_assoc($r);
        $total += $row['price'] * $qty;
    }

    $order_q = "INSERT INTO orders (buyer_id, total_amount, order_date, status) 
                VALUES ('$user_id','$total',NOW(),'pending')";
    if (mysqli_query($conn, $order_q)) {
        $order_id = mysqli_insert_id($conn);
        foreach ($_SESSION['cart'] as $id => $qty) {
            $q = "SELECT price FROM products WHERE product_id='$id'";
            $r = mysqli_query($conn, $q);
            $row = mysqli_fetch_assoc($r);
            $price = $row['price'];
            $oi_q = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                     VALUES ('$order_id','$id','$qty','$price')";
            mysqli_query($conn, $oi_q);
            mysqli_query($conn, "UPDATE products SET quantity = quantity - $qty WHERE product_id='$id'");
        }
        unset($_SESSION['cart']);
        $response['success'] = true;
        $response['message'] = "Order placed successfully! <a href='orders.html'>View Orders</a>";
    } else {
        $response['message'] = "Error placing order: " . mysqli_error($conn);
    }
    echo json_encode($response); exit;
}
$response['message'] = "Invalid request.";
echo json_encode($response);
