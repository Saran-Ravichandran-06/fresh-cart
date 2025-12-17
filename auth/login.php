<?php
session_start();
include '../includes/db.php';  

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    $query = "SELECT * FROM users WHERE email='$email' AND role='$role'";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['role'] = $row['role']; 

            if ($role == 'buyer') {
                header("Location: ../buyer/dashboard.html");
            } else {
                header("Location: ../seller/dashboard.html");
            }
            exit();
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "No user found with this email and role!";
    }
}
?>
