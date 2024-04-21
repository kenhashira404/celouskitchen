<?php

include 'components/connect.php';

if (isset($_POST['delete_order'])) {
    $order_id = $_POST['order_id'];

    // Assuming 'orders' is the table to delete from
    $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");

    try {
        $delete_order->execute([$order_id]);
        header('Location: orders.php');
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>
