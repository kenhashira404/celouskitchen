<?php
ob_start(); // Start output buffering
session_start();

include 'components/connect.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('location:home.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <style>
        <?php include 'css/style.css' ?>
    </style>

</head>

<body>

    <!-- header section starts  -->
    <?php include 'components/user_header.php'; ?>
    <!-- header section ends -->

    <div class="heading">
        <h3>Orders</h3>
        <p><a href="html.php">Home</a> <span> / Orders</span></p>
    </div>

    <section class="orders">

        <h1 class="title">Your Orders</h1>

        <div class="box-container">

            <?php

            if ($user_id == '') {
                echo '<p class="empty">Please login to see your orders</p>';
            } else {
                $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
                $select_orders->execute([$user_id]);

                if ($select_orders->rowCount() > 0) {
                    while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <div class="box">
                            <p>Placed on: <span><?= $fetch_orders['placed_on']; ?></span></p>
                            <p>Name: <span><?= $fetch_orders['name']; ?></span></p>
                            <p>Email: <span><?= $fetch_orders['email']; ?></span></p>
                            <p>Number: <span><?= $fetch_orders['number']; ?></span></p>
                            <p>Address: <span><?= $fetch_orders['address']; ?></span></p>
                            <p>Payment Method: <span><?= $fetch_orders['method']; ?></span></p>
                            <p>Your Orders: <span><?= $fetch_orders['total_products']; ?></span></p>
                            <p>Total Price: <span>₱<?= $fetch_orders['total_price']; ?>/-</span></p>
                            <p>Payment Status: <span style="color:<?php echo ($fetch_orders['payment_status'] == 'pending') ? 'red' : 'green'; ?>"><?= $fetch_orders['payment_status']; ?></span></p>

                            <!-- Fetch and display messages for a specific order -->
                            <div class="messages">
                                <?php
                                $order_id = $fetch_orders['id']; // Assuming 'id' is the order_id
                                $select_messages = $conn->prepare("SELECT * FROM `messages` WHERE order_id = ?");
                                $select_messages->execute([$order_id]);

                                if ($select_messages->rowCount() > 0) {
                                    while ($fetch_message = $select_messages->fetch(PDO::FETCH_ASSOC)) {
                                        echo '<p>Message from Admin: <span>' . $fetch_message['message'] . '</span></p>';
                                        // Add other message details as needed
                                    }
                                } else {
                                    echo '<p>No messages for this order</p>';
                                }
                                ?>
                            </div>

                            

                            <!-- Action buttons based on payment status -->
                            <form action="" method="POST">
                                <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">

                                <?php if ($fetch_orders['payment_status'] == 'pending') { ?>
                                    <!-- Cancel Order button -->
                                    <input type="submit" value="Cancel Order" class="btn" name="cancel_order">
                                <?php } elseif ($fetch_orders['payment_status'] == 'declined' || $fetch_orders['payment_status'] == 'cancelled' || $fetch_orders['payment_status'] == 'completed') { ?>
                                    <!-- Delete Order button -->
                                    <input type="submit" value="Delete Order" class="btn" name="delete_order" onclick="return confirm('Delete this order?');">
                                <?php } ?>
                            </form>

                            <?php
                            // Handle Cancel Order and Delete Order actions
                            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                if (isset($_POST['cancel_order']) && $fetch_orders['payment_status'] == 'pending') {
                                    $order_id = $_POST['order_id'];
                                    // Assuming 'order_status' is the column to update
                                    $update_status = $conn->prepare("UPDATE `orders` SET payment_status = 'cancelled' WHERE id = ?");
                                    $update_status->execute([$order_id]);
                                    header('Location: orders.php');
                                } elseif (isset($_POST['delete_order'])) {
                                    $order_id = $_POST['order_id'];
                                    // Assuming 'orders' is the table to delete from
                                    $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
                                    $delete_order->execute([$order_id]);
                                    header('Location: orders.php');
                                }
                            }
                            ?>

                        </div>
            <?php
                    }
                } else {
                    echo '<p class="empty">No orders placed yet!</p>';
                }
            }
            ?>

        </div>

    </section>

    <!-- footer section starts  -->
    
    <!-- footer section ends -->

    <!-- custom js file link  -->
    <script src="js/script.js"></script>

</body>

</html>

<?php
ob_end_flush(); // Flush the buffer and send output to the browser
?>
