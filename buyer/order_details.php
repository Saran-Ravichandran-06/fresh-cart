<?php
include('../includes/db.php'); // adjust path as needed
header('Content-Type: text/html; charset=utf-8');

// 🧠 Validate order_id
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<h3 style='color:red; text-align:center;'>Invalid Order ID</h3>";
    exit;
}

$order_id = intval($_GET['id']);

// 🧠 Fetch order info from orders table
$orderQuery = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
$orderQuery->bind_param("i", $order_id);
$orderQuery->execute();
$orderResult = $orderQuery->get_result();

if ($orderResult->num_rows === 0) {
    echo "<h3 style='color:red; text-align:center;'>No order found!</h3>";
    exit;
}

$order = $orderResult->fetch_assoc();

// 🧠 Fetch order items and product details
$itemQuery = $conn->prepare("
    SELECT oi.*, p.name AS product_name
    FROM order_items oi
    JOIN products p ON oi.product_id = p.product_id
    WHERE oi.order_id = ?
");
$itemQuery->bind_param("i", $order_id);
$itemQuery->execute();
$items = $itemQuery->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Details - #<?php echo $order_id; ?></title>
  <link rel="stylesheet" href="/fruit_veggie_store/assets/css/style.css">
  <script src="/fruit_veggie_store/assets/js/navbar.js" defer></script>
  <style>
    body {
      background: #f3f8f3;
      font-family: 'Poppins', sans-serif;
    }
    .details-container {
      width: 80%;
      margin: 40px auto;
      background: rgba(255, 255, 255, 0.9);
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      color: #2e7d32;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 12px;
      text-align: center;
    }
    th {
      background-color: #2cc15d;
      color: black;
    }
    .summary {
      margin-top: 25px;
      text-align: right;
      font-weight: bold;
      font-size: 18px;
    }
    a.back-link {
      display: inline-block;
      margin-bottom: 15px;
      color: #2e7d32;
      text-decoration: none;
      font-weight: 600;
    }
    a.back-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="details-container">
    <a href="orders.html" class="back-link">← Back to Orders</a>
    <h2>Order Details - #<?php echo $order['order_id']; ?></h2>
    <p><strong>Status:</strong> <?php echo ucfirst($order['status']); ?></p>
    <p><strong>Order Date:</strong> <?php echo date('d M Y, h:i A', strtotime($order['order_date'])); ?></p>

    <table>
      <thead>
        <tr>
          <th>Product</th>
          <th>Price (₹)</th>
          <th>Quantity</th>
          <th>Subtotal (₹)</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $total = 0;
        while ($item = $items->fetch_assoc()):
          $subtotal = $item['price'] * $item['quantity'];
          $total += $subtotal;
        ?>
        <tr>
          <td><?php echo htmlspecialchars($item['product_name']); ?></td>
          <td><?php echo number_format($item['price'], 2); ?></td>
          <td><?php echo $item['quantity']; ?></td>
          <td><?php echo number_format($subtotal, 2); ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <div class="summary">
      Total Amount: ₹<?php echo number_format($total, 2); ?>
    </div>
  </div>
</body>
</html>
