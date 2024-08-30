<?php

declare(strict_types=1);
require './server/session.php';
require './server/db.php';

// Connect to database
$connection = connectToDatabase();

// Query the top 10 featured menu items
$sql = "
SELECT m.id, m.title, m.description, m.price, c.title AS category_title
FROM menu_items as m
JOIN categories as c
ON m.category_id = c.id
LIMIT 10;";

// Query all menu items
$results = mysqli_query($connection, $sql);
if (mysqli_num_rows($results) <= 0) {
    $message_error = "No items found";
} else {
    $menu_items = mysqli_fetch_all($results, MYSQLI_ASSOC);
}
mysqli_close($connection);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        header('location:login.php');
        exit;
    }
    if (isset($_POST['add_to_cart'])) {
        $item_id = $_POST['item_id'];
        $title = $_POST['title'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];
        $total = $price * $quantity;
        $user_id = $_SESSION['user_id'];

        // Connect to db
        $connection = connectToDatabase();
        $cart_sql = "
        SELECT * FROM cart AS c
        JOIN menu_items AS m
        ON c.menu_item_id = m.id
        WHERE m.title = ? AND c.user_id = ?
        ";
        $check_cart_numbers = $connection->prepare($cart_sql);
        $check_cart_numbers->execute([$title, $user_id]);
        $check_cart_numbers->store_result();

        if ($check_cart_numbers->num_rows() > 0) {
            $message_error = 'already added to cart!';
        } else {
            $insert_to_cart_query = "
            INSERT INTO cart (user_id, menu_item_id, quantity) VALUES(?,?,?);
            ";
            $insert_cart = $connection->prepare($insert_to_cart_query);
            $insert_cart->execute([$user_id, $item_id, $quantity]);
            $insert_cart->close();
            $message_success = 'added to cart!';
        }
        $check_cart_numbers->close();
        mysqli_close($connection);
    }
}

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
    <link rel="stylesheet" href="./web/common/header.css">
    <link rel="stylesheet" href="./web/common/footer.css">
    <link rel="stylesheet" href="./styles/menu.css">
</head>

<body>
    <?php require './web/common/header.php'; ?>
    <section class="menu" id="Menu">
        <h3 class="sub-heading"> Our menu</h3>
        <h1 class="heading"> Today's special</h1>
        <?php if (isset($GLOBALS['message_error'])): ?>
            <p class="error"><?php echo $GLOBALS['message_error'] ?></p>
        <?php elseif (isset($GLOBALS['message_success'])): ?>
            <p class="success"><?php echo $GLOBALS['message_success'] ?></p>
        <?php endif; ?>
        <div class="box-container">
            <?php if (isset($menu_items)) : ?>
                <?php foreach ($menu_items as $item) : ?>
                    <form action="" method="POST">
                        <div class="box">
                            <input type="hidden" name="item_id" value="<?= $item['id']; ?>">
                            <input type="hidden" name="title" value="<?= $item['title']; ?>">
                            <input type="hidden" name="price" value="<?= $item['price']; ?>">
                            <img src="images/img4.png.avif" alt="">
                            <h3><?= $item['title'] ?></h3>
                            <span>Ksh <?= $item['price'] ?></span>
                            <br />
                            Quantity: <input type="number" name="quantity" maxlength="2" value="1">
                            <br />
                            <input type="submit" class="btn" name="add_to_cart" value="Add to cart"><br />
                        </div>
                    </form>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
    <?php require './web/common/footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="./index.js"></script>
</body>

</html>