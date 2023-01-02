<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['addTOcart'])){
   $product_id = $_POST['product_id'];
   $product_name = $_POST['name'];
   $product_price = $_POST['price'];
   $product_image = $_POST['image'];
   $product_quantity = $_POST['quantity'];

   $send_to_cart = $conn->prepare("INSERT INTO `cart` (user_id , product_id , name , price , image , quantity)
                                    VALUES (? , ? , ? , ?, ? , ?)"); 
   $send_to_cart->execute([$user_id , $product_id , $product_name , $product_price, $product_image, $product_quantity]);

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<div class="home-bg">

   <section class="home">

      <div class="swiper home-slider">
      
         <div class="swiper-wrapper">

            <div class="swiper-slide slide">
               <div class="image">
                  <img src="./images/home-img-1.png" alt="">
               </div>
               <div class="content">
                  <span>upto 50% off</span>
                  <h3>latest smartphones</h3>
                  <a href="shop.php" class="btn">shop now</a>
               </div>
            </div>

            <div class="swiper-slide slide">
               <div class="image">
                  <img src="images/home-img-2.png" alt="">
               </div>
               <div class="content">
                  <span>upto 50% off</span>
                  <h3>latest watches</h3>
                  <a href="shop.php" class="btn">shop now</a>
               </div>
            </div>

            <div class="swiper-slide slide">
               <div class="image">
                  <img src="images/home-img-3.png" alt="">
               </div>
               <div class="content">
                  <span>upto 50% off</span>
                  <h3>latest headsets</h3>
                  <a href="shop.php" class="btn">shop now</a>
               </div>
            </div>

         </div>

         <div class="swiper-pagination"></div>

      </div>

   </section>

</div>



<section class="category">

   <h1 class="heading">shop by category</h1>

   <div class="swiper category-slider">

   <div class="swiper-wrapper">

   <a href="category.php?category=1" class="swiper-slide slide">
      <img src="images/icon-1.png" alt="">
      <h3>Abdelmajied</h3>
   </a>

   <a href="category.php?category=4" class="swiper-slide slide">
      <img src="images/icon-2.png" alt="">
      <h3>admin</h3>
   </a>

   <a href="category.php?category=5" class="swiper-slide slide">
      <img src="images/icon-3.png" alt="">
      <h3>asem</h3>
   </a>

   <div class="swiper-pagination"></div>

   </div>

</section>

<section class="home-products">

   <h1 class="heading">Sales Product</h1>

   <div class="swiper products-slider">

   <div class="swiper-wrapper">

   <?php
     $select_products = $conn->prepare("SELECT * FROM `products` WHERE is_sale='1'"); 
     $select_products->execute();
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
         $i=0;
   ?>
   <form action="" method="post" class="swiper-slide slide" style="height:430px">
      <input type="hidden" name="product_id" value="<?= $fetch_product['product_id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
      <?php 
      if ($fetch_product['is_sale'] == 1){
         ?>
         <input type="hidden" name="price" value="<?=$fetch_product['price_discount'];?>">
         <?php
      } else {
         ?>
         <input type="hidden" name="price" value="<?=$fetch_product['price'];?>">
         <?php
      }
      ?>
      <input type="hidden" name="image" value="<?= $fetch_product['image']; ?>">
      <a href="quick_view.php?pid=<?= $fetch_product['product_id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_product['image']; ?>" alt="">
      <div class="name"><?= $fetch_product['name']; ?></div>
      <?php $product_category = $conn->prepare("SELECT * 
                                        FROM `products`
                                        INNER JOIN `category` ON products.category_id = category.category_id");
                  $product_category->execute();
                  if($product_category->rowCount() > 0){
                     while($fetch_product_category = $product_category->fetch(PDO::FETCH_ASSOC)){ 
                        if($i==0 && $fetch_product['category_id'] == $fetch_product_category['category_id'] ){
                        $i++;
            ?>
                        <div class="details" style="color : rgb(133, 132, 132); font-size:15px"><span>Category : <?= $fetch_product_category['category_name']; ?></span>
      </div>
            <?php 
                        }
                     }
                  }
            ?>
      <div class="flex">

         <?php if ($fetch_product['is_sale'] == 1){ ?>

            <div class="price"><span><del style="text-decoration:line-through; color:silver">$<?= $fetch_product['price']; ?></del><ins style="color:green; padding:20px 0px"> $<?=$fetch_product['price_discount'];?></ins> </span></div>

         <?php } else { ?>

            <div class="name" style="color:green;">$<?= $fetch_product['price']; ?></div> <?php } ?>

         <?php if ($fetch_product['category_id'] != '1'){?>

            <input type="number" name="quantity" class="qty" min="1" max="99" value="1">

         <?php } else { ?>
            <input type="hidden" name="quantity" value="1">
         <?php } ?> 

      </div>
      <button type="submit" class="btn" name="addTOcart">Add To Cart</button>
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">no products added yet!</p>';
   }
   ?>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>









<?php include 'components/footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="js/script.js"></script>

<script>

var swiper = new Swiper(".home-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
    },
});

 var swiper = new Swiper(".category-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      0: {
         slidesPerView: 2,
       },
      650: {
        slidesPerView: 3,
      },
      768: {
        slidesPerView: 4,
      },
      1024: {
        slidesPerView: 5,
      },
   },
});

var swiper = new Swiper(".products-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      550: {
        slidesPerView: 2,
      },
      768: {
        slidesPerView: 2,
      },
      1024: {
        slidesPerView: 3,
      },
   },
});

</script>

</body>
</html>