<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restweb: About Us</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./styles/global.css">
    <link rel="stylesheet" href="./web/common/header.css">
    <link rel="stylesheet" href="./web/common/footer.css">
    <link rel="stylesheet" href="./styles/about.css">
    <link rel="stylesheet" href="./styles/popular-dishes.css">
</head>

<body>
    <?php require './web/common/header.php'; ?>
    <section class="about dishes" id="About">
        <h3 class="sub-heading"> About us</h3>
        <h1 class="heading"> Why choose us</h1>
        <div class="row">
            <div class="image">
                <img src="images/rudys.jpg" alt="">
            </div>
            <div class="content">
                <h3>Best food in the country</h3>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Repellat iusto ab iste fuga rem molestiae asperiores exercitationem in distinctio inventore, a doloremque perferendis ipsam quos voluptatum dignissimos consectetur sint incidunt.</p>
                <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Reiciendis beatae sed, culpa et rerum quod cum in voluptate perspiciatis eaque, cumque nam blanditiis, nemo maxime. Dolores doloribus ipsa quia harum?</p>
                <div class="icons-container">
                    <div class="icons">
                        <i class="fas fa-shipping-fast"></i>
                        <span>Free Delivery</span>
                    </div>
                    <div class="icons">
                        <i class="fas fa-dollar-sign"></i>
                        <span>Easy payments</span>
                    </div>
                    <div class="icons">
                        <i class="fas fa-headset"></i>
                        <span>24/7 service</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php require './web/common/footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="./index.js"></script>
</body>

</html>