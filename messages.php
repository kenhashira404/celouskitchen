<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_message = $conn->prepare("DELETE FROM `messages` WHERE id = ?");
    $delete_message->execute([$delete_id]);
    header('location:messages.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <style>
        <?php include '../css/admin_style.css' ?>
    </style>

</head>

<body>

    <?php include '../components/admin_header.php' ?>

    <!-- messages section starts  -->

    <section class="messages">

        <h1 class="heading">Messages</h1>

        <div class="box-container">

            <?php
            $select_messages = $conn->prepare("
                SELECT m.*, u.name AS user_name, u.number AS user_number, u.email AS user_email
                FROM `messages` m
                JOIN `users` u ON m.user_id = u.id
            ");
            $select_messages->execute();

            if ($select_messages->rowCount() > 0) {
                while ($fetch_messages = $select_messages->fetch(PDO::FETCH_ASSOC)) {
            ?>
                    <div class="box">
                        <p>Customer Name: <span><?= $fetch_messages['user_name']; ?></span></p>
                        <p>Customer Number: <span><?= $fetch_messages['user_number']; ?></span></p>
                        <p>Customer Email: <span><?= $fetch_messages['user_email']; ?></span></p>
                        <p>Message: <span><?= $fetch_messages['message']; ?></span></p>
                        <a href="messages.php?delete=<?= $fetch_messages['id']; ?>" class="delete-btn" onclick="return confirm('Delete this message?');">Delete</a>
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">You have no messages</p>';
            }
            ?>

        </div>

    </section>

    <!-- messages section ends -->

    <!-- custom js file link  -->
    <script src="../js/admin_script.js"></script>

</body>

</html>
