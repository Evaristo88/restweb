<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rudy's: About Us</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./styles/global.css">
    <link rel="stylesheet" href="./styles/order.css">
    <link rel="stylesheet" href="./web/common/header.css">
    <link rel="stylesheet" href="./web/common/footer.css">
</head>

<body>
    <?php require './web/common/header.php'; ?>
    <section class="order" id="Order">
        <h3 class="sub-heading"> Order now</h3>
        <h1 class="heading"> Free and fast</h1>
        <form action="">
            <div class="inputBox">
                <div class="input">
                    <span>Your name</span>
                    <input type="text" placeholder="Enter your name">
                </div>
                <div class="input">
                    <span>Your number</span>
                    <input type="tel" placeholder="Enter your number">
                </div>
            </div>
            <div class="inputBox">
                <div class="input">
                    <span>Your order</span>
                    <input type="text" placeholder="Enter food name">
                </div>
                <div class="input">
                    <span>Payment method</span>
                    <input type="test" placeholder="How to pay">
                    <select name="" id="">
                        <option value="">M-pesa</option>
                        <option value="">Card payments</option>
                    </select>
                </div>
            </div>
            <div class="inputBox">
                <div class="input">
                    <span>How much</span>
                    <input type="number" placeholder="How many orders">
                </div>
                <div class="input">
                    <span>date and time</span>
                    <input type="datetime-local">
                </div>
            </div>
            <div class="inputBox">
                <div class="input">
                    <span>Your address</span>
                    <textarea name="" placeholder="Enter your address" id="" cols="30" rows="10"></textarea>
                </div>
                <div class="input">
                    <span>Your message</span>
                    <textarea name="" placeholder="Enter your message" id="" cols="30" rows="10"></textarea>
                </div>
            </div>
            <input type="submit" value="order now" class="btn">
        </form>

    </section>

    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="./index.js"></script>
</body>
<?php require './web/common/footer.php' ?>

</html>