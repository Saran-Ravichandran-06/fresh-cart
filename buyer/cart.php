
<?php
session_start();
header('Content-Type: application/json');
include '../includes/db.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$response = ["success" => false, "cart" => []];

QUEST_METHOD'] === 'POST' && isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $id = intval($_POST['product_id']);
    $qty = intval($_POST['quantity']);
    if ($qty > 0) {
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] += $qty;
        } else {
            $_SESSION['cart'][$id] = $qty;
        }
        $response['success'] = true;
        $response['message'] = "Added to cart.";
    } else {
        $response['message'] = "Invalid quantity.";
    }
    echo json_encode($response); exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove']) && isset($_POST['product_id'])) {
    $id = intval($_POST['product_id']);
    unset($_SESSION['cart'][$id]);
    $response['success'] = true;
    $response['message'] = "Removed from cart.";
    echo json_encode($response); exit;
}


$cart = [];
foreach ($_SESSION['cart'] as $id => $qty) {
    $q = "SELECT * FROM products WHERE product_id='$id'";
    $r = mysqli_query($conn, $q);
    if ($row = mysqli_fetch_assoc($r)) {
        $cart[] = [
            "product_id" => $row['product_id'],
            "name" => $row['name'],
            "price" => $row['price'],
            "quantity" => $qty
        ];
    }
}
$response['success'] = true;
$response['cart'] = $cart;
echo json_encode($response);
