<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

if (isset($_GET['get_id'])) {
    $get_id = $_GET['get_id'];
} else {
    $get_id = '';
    header('location:all_posts.php');
}

if(isset($_POST['delete_review'])){

    $delete_id = $_POST['delete_id'];
    $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);
 
    $verify_delete = $conn->prepare("SELECT * FROM `reviews` WHERE id = ?");
    $verify_delete->execute([$delete_id]);
    
    if($verify_delete->rowCount() > 0){
       $delete_review = $conn->prepare("DELETE FROM `reviews` WHERE id = ?");
       $delete_review->execute([$delete_id]);
       $success_msg[] = 'Review deleted!';
    }else{  
       $warning_msg[] = 'Review already deleted!';
    }
 
 }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>posts</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        <?php include 'css/post.css' ?>
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
        integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>
    <?php include 'components/user_header.php'; ?>

    <section class="view-post">

        <div class="heading">
            <h1>post details</h1> <a href="all_post.php" class="inline-option-btn" style="margin-top:;">All posts</a>
        </div>

        <div class="box-container">

            <?php $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ? LIMIT 1");
            $select_products->execute([$get_id]);
            if ($select_products->rowCount() > 0) {
                while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {

                    $total_ratings = 0;
                    $rating_1 = 0;
                    $rating_2 = 0;
                    $rating_3 = 0;
                    $rating_4 = 0;
                    $rating_5 = 0;

                    $select_ratings = $conn->prepare("SELECT * FROM reviews WHERE post_id = ?");
                    $select_ratings->execute([$fetch_products["id"]]);
                    $total_reviews = $select_ratings->rowCount();
                    while ($fetch_rating = $select_ratings->fetch(PDO::FETCH_ASSOC)) {
                        $total_ratings += $fetch_rating['rating'];
                        if ($fetch_rating['rating'] == 1) {
                            $rating_1 += $fetch_rating['rating'];
                        }
                        if ($fetch_rating['rating'] == 2) {
                            $rating_2 += $fetch_rating['rating'];
                        }
                        if ($fetch_rating['rating'] == 3) {
                            $rating_3 += $fetch_rating['rating'];
                        }
                        if ($fetch_rating['rating'] == 4) {
                            $rating_4 += $fetch_rating['rating'];
                        }
                        if ($fetch_rating['rating'] == 5) {
                            $rating_5 += $fetch_rating['rating'];
                        }
                    }
                    if ($total_reviews != 0) {
                        $average = round($total_ratings / $total_reviews, 1);
                    } else {
                        $average = 0;
                    }
                    ?>
                    <div class="row">
                        <div class="col">
                            <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
                            <h3 class="title">
                                <?= $fetch_products['name']; ?>
                            </h3>
                        </div>

                        <div class="col">
                            <div class="flex">
                                <div class="total-reviews">
                                    <h3>
                                        <?= $average; ?> <i class="fa-solid fa-star"></i>
                                    </h3>
                                    <p>
                                        <?= $total_reviews; ?> reviews
                                    </p>
                                </div>
                                <div class="total-ratings">
                                    <p>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <span>
                                            <?= $rating_5; ?>
                                        </span>
                                    </p>
                                    <p>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <span>
                                            <?= $rating_4; ?>
                                        </span>
                                    </p>
                                    <p>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <span>
                                            <?= $rating_3; ?>
                                        </span>
                                    </p>
                                    <p>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <span>
                                            <?= $rating_2; ?>
                                        </span>
                                    </p>
                                    <p>
                                        <i class="fa-solid fa-star"></i>
                                        <span>
                                            <?= $rating_1; ?>
                                        </span>
                                    </p>

                                </div>
                            </div>
                        </div>

                    </div>

                    <?php

                }
            } else {
                echo '<p class="empty">post is missing</p>';
            }

            ?>

        </div>
    </section>

    <section class="reviews-container">
        <div class="heading">
            <h1>User's Review</h1> <a href="add_review.php?get_id=<?= $get_id; ?>" class="inline-option-btn"
                style="margin-top:;">Add review</a>
        </div>
        <div class="box-container">

            <?php
            $select_reviews = $conn->prepare("SELECT * FROM `reviews` WHERE post_id = ?");
            $select_reviews->execute([$get_id]);
            if ($select_reviews->rowCount() > 0) {
                while ($fetch_review = $select_reviews->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <div class="box" <?php if ($fetch_review['user_id'] == $user_id) {
                        echo 'style="order: -1;"';
                    }
                    ; ?>>
                        <?php
                        $select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
                        $select_user->execute([$fetch_review['user_id']]);
                        while ($fetch_user = $select_user->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                            <div class="user">
                                <?php if ($fetch_user['image'] != '') { ?>
                                    <img src="uploaded_img/<?= $fetch_user['image']; ?>" alt="">
                                <?php } else { ?>
                                    <h3>
                                        <?= substr($fetch_user['name'], 0, 1); ?>
                                    </h3>
                                <?php }
                                ; ?>
                                <div>
                                    <p>
                                        <?= $fetch_user['name']; ?>
                                    </p>
                                    <span>
                                        <?= $fetch_review['date']; ?>
                                    </span>
                                </div>
                            </div>
                        <?php }
                        ; ?>
                        <div class="ratings">
                            <?php if ($fetch_review['rating'] == 1) { ?>
                                <p style="background:var(--red);"><i class="fas fa-star"></i> <span>
                                        <?= $fetch_review['rating']; ?>
                                    </span></p>
                            <?php }
                            ; ?>
                            <?php if ($fetch_review['rating'] == 2) { ?>
                                <p style="background:var(--orange);"><i class="fas fa-star"></i> <span>
                                        <?= $fetch_review['rating']; ?>
                                    </span></p>
                            <?php }
                            ; ?>
                            <?php if ($fetch_review['rating'] == 3) { ?>
                                <p style="background:var(--orange);"><i class="fas fa-star"></i> <span>
                                        <?= $fetch_review['rating']; ?>
                                    </span></p>
                            <?php }
                            ; ?>
                            <?php if ($fetch_review['rating'] == 4) { ?>
                                <p style="background:var(--main-color);"><i class="fas fa-star"></i> <span>
                                        <?= $fetch_review['rating']; ?>
                                    </span></p>
                            <?php }
                            ; ?>
                            <?php if ($fetch_review['rating'] == 5) { ?>
                                <p style="background:var(--main-color);"><i class="fas fa-star"></i> <span>
                                        <?= $fetch_review['rating']; ?>
                                    </span></p>
                            <?php }
                            ; ?>
                        </div>
                        <h3 class="title">
                            <?= $fetch_review['title']; ?>
                        </h3>
                        <?php if ($fetch_review['description'] != '') { ?>
                            <p class="description">
                                <?= $fetch_review['description']; ?>
                            </p>
                        <?php }
                        ; ?>
                        <?php if ($fetch_review['user_id'] == $user_id) { ?>
                            <form action="" method="post" class="flex-btn">
                                <input type="hidden" name="delete_id" value="<?= $fetch_review['id']; ?>">
                                <a href="update_review.php?get_id=<?= $fetch_review['id']; ?>" class="inline-option-btn">edit
                                    review</a>
                                <input type="submit" value="delete review" class="inline-delete-btn" name="delete_review"
                                    onclick="return confirm('delete this review?');">
                            </form>
                        <?php }
                        ; ?>
                    </div>
                    <?php
                }
            } else {
                echo '<p class="empty">no reviews added yet!</p>';
            }
            ?>

        </div>
    </section>

    <script src="js/script.js"></script>
</body>

</html>