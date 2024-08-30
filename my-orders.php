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
if (isset($_GET['cancel'])) {
    $delete_id = $_GET['cancel'];
    $delete_order = $connection->prepare("DELETE FROM `orders` WHERE `id` = ?");
    $delete_order->execute([$delete_id]);

    // Remove items ordered related to the cancelled order
    $delete_order_items = $connection->prepare("DELETE FROM `order_items` WHERE `order_id` = ?");
    $delete_order_items->execute([$delete_id]);
    header('location:my-orders.php');
}

// Get all orders for the customer.
$query_customer_orders = "
SELECT o.id, o.total_amount, o.order_date, o.status, o.payment_method, o.payment_status
FROM orders as o
WHERE o.user_id = ?
ORDER BY created_at DESC;";

// Get user's products from the cart
$query = $connection->prepare($query_customer_orders);
$query->execute([$_SESSION['user_id']]);
$query->store_result();
$query->bind_result($id, $total_amount, $order_date, $status, $payment_method, $payment_status);

$order_items = [];
while ($query->fetch()) {
    $order_items[] = [
        'id' => $id,
        'total_amount' => $total_amount,
        'order_date' => $order_date,
        'status' => $status,
        'payment_method' => $payment_method,
        'payment_status' => $payment_status,
    ];
}
$query->close();

// Get all product names for each item ordered
foreach ($order_items as $key => $item) {
    $query_order_items = "
    SELECT m.title
    FROM order_items as oi
    JOIN menu_items as m
    ON oi.menu_item_id = m.id
    WHERE oi.order_id = ?;";

    $query = $connection->prepare($query_order_items);
    $query->execute([$item['id']]);
    $query->store_result();
    $query->bind_result($title);

    $order_items[$key]['items'] = [];
    while ($query->fetch()) {
        $order_items[$key]['items'][] = $title;
    }
    $query->close();
}
// var_dump($order_items);
$connection->close();

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
    <link rel="stylesheet" href="./styles/my-orders.css">
    <link rel="stylesheet" href="./styles/checkout.css">
</head>

<body>
    <?php require './web/common/header.php'; ?>
    <section class="my-orders" id="my-orders">
        <h1>My orders</h1>
        <?php if (!empty($order_items)) : ?>
            <div class="my-orders-list">
                <?php foreach ($order_items as $item) : ?>
                    <form action="" method="POST">
                        <div class="my-orders-item">
                            <img src="images/img4.png.avif" alt="">
                            <div class="my-orders-details">
                                <h3><?= ucfirst($item['payment_status']) ?></h3>
                                <p class="">Order date: <?= $item['order_date'] ?></p>
                                <p class="total_amount">Tzs <?= $item['total_amount'] ?></p>
                                <p> Status: <span style="color:<?php if ($item['status'] == 'pending') {
                                                                    echo 'red';
                                                                } else {
                                                                    echo 'green';
                                                                }; ?>"><?= $item['status']; ?></span> </p>
                                <p class="payment_method">Payment method: <?= $item['payment_method'] ?></p>
                                <p> Payment status : <span style="color:<?php if ($item['payment_status'] == 'pending') {
                                                                            echo 'red';
                                                                        } else {
                                                                            echo 'green';
                                                                        }; ?>"><?= $item['payment_status']; ?></span> </p>
                            </div>
                            <div class='quantity-btns'>
                                <a href="my-orders.php?cancel=<?= $item['id']; ?>" class="btn-outline" onclick="return confirm('Cancel this order?');">Cancel</a>
                            </div>
                            <br />
                        </div>
                    </form>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="empty-my-orders">You have no orders yet</p>
        <?php endif; ?>
    </section>
    <?php require './web/common/footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="./index.js"></script>
</body>

</html>