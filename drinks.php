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
WHERE c.title = 'beverage'
LIMIT 10;";
// Query all drink items
$results = mysqli_query($connection, $sql);
if (mysqli_num_rows($results) <= 0) {
    $message = "No drinks found";
} else {
    $drinks = mysqli_fetch_all($results, MYSQLI_ASSOC);
}
mysqli_close($connection);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rudy's: About Us</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./styles/global.css">
    <link rel="stylesheet" href="./styles/popular-dishes.css">
    <link rel="stylesheet" href="./web/common/header.css">
    <link rel="stylesheet" href="./web/common/footer.css">
</head>

<body>
    <?php require './web/common/header.php'; ?>
    <section class="dishes" id="Drinks">
        <h3 class="sub-heading"> Our drinks</h3>
        <h1 class="heading"> Popular drinks</h1>
        <div class="box-container">
            <?php if (isset($drinks)) : ?>
                <?php foreach ($drinks as $drink) : ?>
                    <div class="box">
                        <img src="images/img4.png.avif" alt="">
                        <h3><?= $drink['title'] ?></h3>
                        <span>Ksh <?= $drink['price'] ?></span>
                        <a href="#" class="btn">Add to order</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
    <?php require './web/common/footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="./index.js"></script>
</body>

</html>