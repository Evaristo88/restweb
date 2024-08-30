<?php

declare(strict_types=1);
require './server/session.php';
require './server/db.php';

// Connect to database
$connection = connectToDatabase();
// Query the top 10 featured menu items
$sql = "
SELECT m.title, m.description, m.price, c.title AS category_title
FROM menu_items as m
JOIN categories as c
ON m.category_id = c.id
WHERE featured = 1
LIMIT 10;";
// Query for featured(popular) dishes
$popular_dishes;
$results = mysqli_query($connection, $sql);
if (mysqli_num_rows($results) <= 0) {
    $message = "No popular dishes yet";
} else {
    $popular_dishes = mysqli_fetch_all($results, MYSQLI_ASSOC);
}
mysqli_close($connection);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Popular dishes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./styles/global.css">
    <link rel="stylesheet" href="./styles/popular-dishes.css">
    <link rel="stylesheet" href="./web/common/header.css">
    <link rel="stylesheet" href="./web/common/footer.css">
</head>

<body>
    <?php require './web/common/header.php'; ?>
    <section class="dishes" id="popular-dishes">
        <h3 class="sub-heading"> Our dishes</h3>
        <h1 class="heading"> Popular dishes</h1>
        <div class="box-container">
            <?php if (isset($popular_dishes)): ?>
                <?php foreach ($popular_dishes as $dish) : ?>
                    <div class="box">
                        <img src="images/imgg5.jpg" alt="">
                        <h3><?= $dish['title'] ?></h3>
                        <span>Ksh <?= $dish['price'] ?></span>
                        <a href="#" class="btn">Add to order</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="message"><?= $message ?></p>
            <?php endif; ?>
        </div>
    </section>
    <?php require './web/common/footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="./index.js"></script>
</body>

</html>