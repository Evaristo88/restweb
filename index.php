<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rudy's</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./styles/global.css">
    <link rel="stylesheet" href="./styles/index.css">
    <link rel="stylesheet" href="./web/common/header.css">
    <link rel="stylesheet" href="./web/common/footer.css">
</head>

<body>
    <?php require './web/common/header.php'; ?>
    <!-- HOME PAGE -->
    <section class="home" id="home">
        <div class="swiper home-slider">
            <div class="swiper-wrapper wrapper">
                <div class="swiper-slide slide">
                    <div class="content">
                        <span>Our Special Dishes</span>
                        <h3>spicy noodles</h3>
                        <p>A delicacy that live people with nolstagic feelings.</p>
                        <a href="#Order" class="btn">Order now</a>
                    </div>
                    <div class="img">
                        <img src="images/img1.png.jpg" alt="">
                    </div>
                </div>
                <div class="swiper-slide slide">
                    <div class="content">
                        <span>Our Special Dishes</span>
                        <h3>pepperoni pizza</h3>
                        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit.</p>
                        <a href="#Order" class="btn">Order now</a>
                    </div>
                    <div class="img">
                        <img src="images/img2.png.jpg" alt="">
                    </div>
                </div>
                <div class="swiper-slide slide">
                    <div class="content">
                        <span>Our Special Dishes</span>
                        <h3>fried chicken</h3>
                        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit.</p>
                        <a href="#Order" class="btn">Order now</a>
                    </div>
                    <div class="img">
                        <img src="images/img3.png.jpg" alt="">
                    </div>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="index.js"></script>
</body>
</html>