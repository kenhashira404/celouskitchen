<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('location:home.php');
}

if (isset($_POST['cancel_order'])) {
    $order_id = $_POST['order_id'];

    // Add logic to update the order status to "cancelled" in the database
    $cancel_order = $conn->prepare("UPDATE `orders` SET payment_status = 'cancelled' WHERE id = ?");
    $cancel_order->execute([$order_id]);

    header('location: orders.php'); // Redirect to the orders page
}

?>
