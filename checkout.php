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

// Get all products from customer's cart.
$query_customer_cart = "
SELECT c.id, c.quantity, m.title, m.price
FROM cart as c
JOIN menu_items as m
ON c.menu_item_id = m.id
WHERE c.user_id = ?
ORDER BY created_at DESC;";

// Get user's products from the cart
$query = $connection->prepare($query_customer_cart);
$query->execute([$_SESSION['user_id']]);
$query->store_result();
$query->bind_result($id, $quantity, $title, $price);

// Store cart items
$total_products = 0;
$sub_total = 0.00;
$total_cost = 0.00;
$cart_items = [];
while ($query->fetch()) {
    $cart_items[] = [
        'id' => $id,
        'title' => $title,
        'price' => $price,
        'quantity' => $quantity,
    ];
    $total_products += 1 * $quantity;
    $sub_total += $quantity * $price;
}
// Add vat cost
$vat = $sub_total * 0.16;
$total_cost = $sub_total + $vat;
// Close database connection when done
$query->close();
$connection->close();


if ($_SERVER["REQUEST_METHOD"] === "POST" && $_POST['confirm_order']) {
    if (!isset($_SESSION['user_id'])) {
        header('location:login.php');
        exit();
    }
    // Get payment method
    $payment_method = $_POST['payment_method'];
    // New orders have pending state to await payment
    $status = 'pending';
    // Create order
    $connection = connectToDatabase();
    $query = $connection->prepare("INSERT INTO orders (user_id, total_amount, status, payment_method) VALUES (?, ?, ?, ?)");
    $query->bind_param("idss", $_SESSION['user_id'], $total_cost, $status, $payment_method);
    $query->execute();

    // Get order id
    $new_order_id = $query->insert_id;
    $query->close();

    // Create an order item for each product from the customer's cart
    $query = $connection->prepare("INSERT INTO order_items (order_id, menu_item_id, quantity) VALUES (?, ?, ?)");
    $query->bind_param("iii", $order_id, $item_id, $item_quantity);
    foreach ($cart_items as $item) {
        $order_id = $new_order_id;
        $item_id = $item['id'];
        $item_quantity = $item['quantity'];
        $query->execute();
    }
    $query->close();
    // Clear the user's cart
    $query = $connection->prepare("DELETE FROM cart WHERE user_id = ?");
    $query->execute([$_SESSION['user_id']]);
    $query->close();
    // Close database connection when done
    $connection->close();
    // Redirect to orders page
    header('Location:/my-orders.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restweb: Checkout</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./styles/global.css">
    <link rel="stylesheet" href="./web/common/header.css">
    <link rel="stylesheet" href="./web/common/footer.css">
    <link rel="stylesheet" href="./styles/checkout.css">
</head>

<body>
    <?php require './web/common/header.php'; ?>
    <section class="checkout" id="checkout">
        <div class="row">
            <div class="content">
                <h3>Order summary</h3>
                <p><?= $total_products ?> products.</p>
                <?php if (!empty($cart_items)): ?>
                    <ul>
                        <?php foreach ($cart_items as $item): ?>
                            <li class="order-item"><?= $item['quantity'] ?> x <?= $item['title'] ?> <span class="price">Tsh <?= $item['price'] ?></span></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <div>
                    <!-- Payment method -->
                    <h3>Payment method</h3>
                    <form action="checkout.php" method="post">
                        <div>
                            <input type="radio" name="payment_method" value="cash" id="cash" checked aria-checked <?php if (isset($payment_method) && $payment_method === 'cash') echo "checked"; ?> />
                            <label for="cash">Cash</label>
                            <input type="radio" name="payment_method" value="mpesa" id="mpesa" <?php if (isset($payment_method) && $payment_method === 'mpesa') echo "checked"; ?> />
                            <label for="mpesa">M-Pesa</label>
                            <input type="radio" name="payment_method" value="card" id="card" <?php if (isset($payment_method) && $payment_method === 'card') echo "checked"; ?> />
                            <label for="card">Card</label>
                        </div>
                        <div>
                            <!-- Calulate costs -->
                            <p>Subtotal: Tzs<?= $sub_total ?></p>
                            <p>VAT 16%: Tzs<?= $vat ?></p>
                            <p>Total: Tzs<?= $total_cost ?></p>
                        </div>
                        <input type="submit" name="confirm_order" value="Confirm order"/>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <?php require './web/common/footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="./index.js"></script>
</body>

</html>