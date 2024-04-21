<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}
;

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>about</title>
   <link rel="shortcut icon" href="images/fav.png" type="image/x-icon">
   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

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
      <h3>about us</h3>
      <p><a href="home.php">home</a> <span> / about</span></p>
   </div>

   <!-- about section starts  -->

   <section class="about">

      <div class="row">

         <div class="content">
            <h3>why choose us?</h3>
            <p>Because We Transform Every Occasion into a Sweet Symphony of Flavor and Elegance. From our luscious leche
               flan to personalized cakes, we blend passion with perfection to make your events truly unforgettable. Let
               your desserts reflect the essence of your celebration – choose Celous Kitchen, where every bite is a
               masterpiece!"!</p>
            <a href="menu.php" class="btn">our menu</a>
         </div>

      </div>

   </section>

   <!-- about section ends -->

   <!-- steps section starts  -->

   <section class="steps">

      <h1 class="title">simple steps</h1>

      <div class="box-container">

         <div class="box">
            <img src="images/order.png" alt="">
            <h3>choose order</h3>
            <p> Select your favorites with ease and customize your culinary experience.</p>
         </div>

         <div class="box">
            <img src="images/fast-delivery.png" alt="">
            <h3>fast delivery</h3>
            <p>Swift and reliable service, ensuring your cravings are met promptly at your doorstep.</p>
         </div>

         <div class="box">
            <img src="images/customer.png" alt="">
            <h3>enjoy food</h3>
            <p>Indulge in a delightful culinary journey, savoring every moment of flavor and satisfaction.</p>
         </div>

      </div>

   </section>

   <!-- steps section ends -->

   <!-- reviews section starts  -->

   

   <!-- reviews section ends -->




   <!-- footer section starts  -->
   <?php include 'components/footer.php'; ?>
   <!-- footer section ends -->=


   <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

   <!-- custom js file link  -->
   <script src="js/script.js"></script>


</body>

</html>