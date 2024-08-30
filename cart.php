<?php

declare(strict_types=1);
require_once './server/session.php';
require_once './server/db.php';

if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit;
}

// Connect to database
$connection = connectToDatabase();

// Cancel order if requested
if (isset($_GET['clear'])) {
    $delete_cart = $connection->prepare("DELETE FROM `cart` WHERE user_id = ?");
    $delete_cart->execute([$_SESSION['user_id']]);
    header('location:cart.php');
}

// Customer's cart items query
$query_customer_cart = "
SELECT c.id, c.quantity, m.title, m.description, m.price, m.id AS item_id
FROM cart as c
JOIN menu_items as m
ON c.menu_item_id = m.id
WHERE c.user_id = ?
ORDER BY created_at DESC;";

// Get user's products from the cart
$query = $connection->prepare($query_customer_cart);
$query->execute([$_SESSION['user_id']]);
$query->store_result();
$query->bind_result($id, $quantity, $title, $description, $price, $item_id);

// Store cart items
$total_cost = 0.00;
$cart_items = [];
while ($query->fetch()) {
    $cart_items[] = [
        'id' => $item_id,
        'title' => $title,
        'description' => $description,
        'price' => $price,
        'quantity' => $quantity,
    ];
    $total_cost += $quantity * $price;
}
// Close database connection when done
$query->close();
$connection->close();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
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
            $message = 'already added to cart!';
        } else {
            $insert_to_cart_query = "
            INSERT INTO cart (user_id, menu_item_id, quantity) VALUES(?,?,?);
            ";
            $insert_cart = $connection->prepare($insert_to_cart_query);
            $insert_cart->execute([$user_id, $item_id, $quantity]);
            $insert_cart->close();
            $message = 'added to cart!';
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
    <title>Rudy's: My Cart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./styles/global.css">
    <link rel="stylesheet" href="./web/common/header.css">
    <link rel="stylesheet" href="./web/common/footer.css">
    <link rel="stylesheet" href="./styles/menu.css">
    <link rel="stylesheet" href="./styles/cart.css">
</head>

<body>
    <?php require './web/common/header.php'; ?>
    <section class="cart" id="cart">
        <h1 class="heading">My cart</h1>
        <!-- <h3 class="sub-heading">My cart</h3> -->
        <?php if (!empty($cart_items)) : ?>
            <div class="cart-list">
                <?php foreach ($cart_items as $item) : ?>
                    <form action="" method="POST">
                        <div class="cart-item">
                            <input type="hidden" name="item_id" value="<?= $item['id']; ?>">
                            <!-- <input type="hidden" name="item_id" value="<?= $item['id']; ?>"> -->
                            <input type="hidden" name="title" value="<?= $item['title']; ?>">
                            <input type="hidden" name="price" value="<?= $item['price']; ?>">
                            <input type="hidden" name="price" value="<?= $item['quantity']; ?>">
                            <img src="images/img4.png.avif" alt="">
                            <div class="cart-details">
                                <h3><?= ucfirst($item['title']) ?></h3>
                                <span class="price">Ksh <?= $item['price'] ?></span>
                            </div>
                            <div class='quantity-btns'>
                                <input type="button" class="btn-outline" name="increase_item" value="+">
                                <span class="quantity"><?= $item['quantity'] ?></span>
                                <input type="button" class="btn-outline" name="decrease_item" value="-">
                            </div>
                            <br />
                        </div>
                    </form>
                <?php endforeach; ?>
            </div>
            <p class="total-cost">Total cost: <span><?= $total_cost ?></span></p>
            <a href="cart.php?clear=true" class="btn-outline" onclick="return confirm('Clear your cart?');">Clear cart</a>
            <a href="/checkout.php" class="btn-primary checkout" name="checkout">
                Proceed to checkout
            </a>
        <?php else: ?>
            <p class="empty-cart">Your cart is empty</p>
        <?php endif; ?>
    </section>
    <?php require './web/common/footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="./index.js"></script>
</body>

</html>