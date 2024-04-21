<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

if (isset($_POST['update_payment'])) {
    $order_id = $_POST['order_id'];
    $payment_status = $_POST['payment_status'];
    $update_status = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
    $update_status->execute([$payment_status, $order_id]);
    $message[] = 'Payment status updated!';
}

if (isset($_POST['send_message'])) {
    $order_id = $_POST['order_id'];
    $admin_message = $_POST['admin_message'];

    // Insert the message into the messages table
    $insert_message = $conn->prepare("INSERT INTO `messages` (order_id, admin_id, message) VALUES (?, ?, ?)");
    $insert_message->execute([$order_id, $admin_id, $admin_message]);

    // You can also display a success message if needed
    $message[] = 'Message sent to the user!';
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
    $delete_order->execute([$delete_id]);
    header('location:placed_orders.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Placed Orders</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Custom CSS file link -->
    <style>
        <?php include '../css/admin_style.css' ?>
    </style>
</head>

<body>
    <?php include '../components/admin_header.php' ?>

    <!-- Placed Orders section starts -->
    <section class="placed-orders">
        <h1 class="heading">Placed Orders</h1>
        <div class="box-container">
            <?php
            $select_orders = $conn->prepare("SELECT * FROM `orders`");
            $select_orders->execute();
            if ($select_orders->rowCount() > 0) {
                while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
            ?>
                    <div class="box">
                        <p>User ID: <span><?= $fetch_orders['user_id']; ?></span></p>
                        <p>Placed On: <span><?= $fetch_orders['placed_on']; ?></span></p>
                        <p>Name: <span><?= $fetch_orders['name']; ?></span></p>
                        <p>Email: <span><?= $fetch_orders['email']; ?></span></p>
                        <p>Number: <span><?= $fetch_orders['number']; ?></span></p>
                        <p>Address: <span><?= $fetch_orders['address']; ?></span></p>
                        <p>Total Products: <span><?= $fetch_orders['total_products']; ?></span></p>
                        <p>Total Price: <span>₱<?= $fetch_orders['total_price']; ?>/-</span></p>
                        <p>Payment Method: <span><?= $fetch_orders['method']; ?></span></p>
                        <form action="" method="POST">
                            <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
                            <select name="payment_status" class="drop-down">
                                <option value="" selected disabled><?= $fetch_orders['payment_status']; ?></option>
                                <option value="pending">pending</option>
                                <option value="accepted">accepted</option>
                                <option value="On Process">On Process</option>
                                <option value="On the way">On the Way</option>
                                <option value="declined">declined</option>
                                <option value="cancelled">cancelled</option>
                                <option value="completed">completed</option>
                            </select>
                            <div class="flex-btn">
                                <input type="submit" value="update" class="btn" name="update_payment">
                                <a href="placed_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('delete this order?');">delete</a>
                            </div>
                        </form>

                        <!-- Messaging Form -->
                        <form action="" method="POST" class="message-form">
                            <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
                            <textarea name="admin_message" placeholder="Type your message"></textarea>
                            <div class="flex-btn">
                                <input type="submit" value="Send Message" class="btn" name="send_message">
                            </div>
                        </form>
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">No orders placed yet!</p>';
            }
            ?>
        </div
