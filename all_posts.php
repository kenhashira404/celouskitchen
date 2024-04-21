<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
};

?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>posts</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <style>
      <?php include 'css/style.css' ?>
   </style>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
      integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
      crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   
</head>

<body>
   <?php include 'components/user_header.php'; ?>

   <section class="products">

      <h1 class="title">All foods</h1>

      <div class="box-container">

         <?php
         $select_products = $conn->prepare("SELECT * FROM `products`");
         $select_products->execute();
         if ($select_products->rowCount() > 0) {
            while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {

               $post_id = $fetch_products["id"];

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
               <div class="box">
                  <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
                  <div class="name">
                     <?= $fetch_products['name']; ?>
                  </div>
                  <p><i class="fa-solid fa-star"></i><span>
                        <?= $average; ?>
                     </span></p>
                     <a href="view_post.php?get_id=<?=$post_id;?>" class="btn">View Post</a>
               </div>
               <?php
            }
         } else {
            echo '<p class="empty">no products added yet!</p>';
         }
         ?>

      </div>

   </section>
   <?php include 'components/alerts.php'; ?>

   <script src="js/script.js"></script>
</body>

</html>