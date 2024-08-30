<?php

declare(strict_types=1);
require_once './server/session.php';
require_once './server/db.php';
require_once './server/auth.php';
auth_admin();

$connection = connectToDatabase();

// Change order if requested
if (isset($_GET['order_status']) && isset($_GET['order_id'])) {
    $order_status = $_GET['order_status'];
    $query_update_order_status = "UPDATE orders SET status = ? WHERE id = ?";
    $update_order_status = $connection->prepare($query_update_order_status);
    $update_order_status->execute([$order_status, $_GET['order_id']]);
    $update_order_status->close();
    header('location:admin-orders.php');
}

// Change order payment status
if (isset($_GET['payment_status']) && isset($_GET['order_id'])) {
    $payment_status = $_GET['payment_status'];
    $query_update_payment_status = "UPDATE orders SET payment_status = ? WHERE id = ?";
    $update_payment_status = $connection->prepare($query_update_payment_status);
    $update_payment_status->execute([$payment_status, $_GET['order_id']]);
    $update_payment_status->close();
    header('location:admin-orders.php');
}

// Delete order 
if (isset($_GET['order_id']) && isset($_GET['order_action']) && $_GET['order_action'] === 'delete') {
    $query_delete_order = "DELETE FROM orders WHERE id = ?";
    $delete_order = $connection->prepare($query_delete_order);
    $delete_order->execute([$_GET['order_id']]);
    $delete_order->close();
    // Delete order items
    $delete_order_items = $connection->prepare("DELETE FROM `order_items` WHERE `order_id` = ?");
    $delete_order_items->execute([$_GET['order_id']]);
    $delete_order_items->close();
    header('location:admin-orders.php');
}

// Fetch all orders
$query_customer_orders = "
SELECT o.id, o.total_amount, o.order_date, o.status, o.payment_method, o.payment_status, u.first_name
FROM orders AS o
JOIN users AS u
ON o.user_id = u.id
ORDER BY order_date DESC;";
$query = $connection->prepare($query_customer_orders);
$query->execute();
$query->store_result();
$query->bind_result($order_id, $total_amount, $order_date, $status, $payment_method, $payment_status, $customer_first_name);

// Store results
$recent_orders = [];
while ($query->fetch()) {
    $recent_orders[] = [
        'order_id' => $order_id,
        'total_amount' => $total_amount,
        'order_date' => $order_date,
        'status' => $status,
        'payment_method' => $payment_method,
        'payment_status' => $payment_status,
        'customer_first_name' => $customer_first_name
    ];
}
$query->close();

// Close database connection
$connection->close();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Rudy's: Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="styles/global.css" />
    <link rel="stylesheet" href="styles/dashboard.css" />
    <link rel="stylesheet" href="styles/dashboard-nav.css" />
    <link rel="stylesheet" href="styles/dashboard-top-nav.css" />
    <link rel="stylesheet" href="styles/admin-customers.css" />
</head>

<body>
    <div class="container">
        <?php require './dashboard-nav.php' ?>
        <div class="main">
            <?php require './dashboard-top-nav.php' ?>
            <div class="customers">
                <div class="recentOrders">
                    <div class="cardHeader">
                        <h2>Orders</h2>
                    </div>
                    <?php if (!empty($recent_orders)): ?>
                        <table>
                            <thead>
                                <tr>
                                    <td>Customer</td>
                                    <td>Order date</td>
                                    <td>Total</td>
                                    <td>Payment</td>
                                    <td>Order status</td>
                                    <td>Payment status</td>
                                    <td>Actions</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_orders as $order): ?>
                                    <tr>
                                        <td><?= ucfirst($order['customer_first_name']) ?></td>
                                        <td><?= $order['order_date'] ?></td>
                                        <td><?= $order['total_amount'] ?></td>
                                        <td><?= $order['payment_method'] ?></td>
                                        <td>
                                            <select name="order_status" id="order_status"
                                                onchange="window.location=this.value">
                                                <option
                                                    value="admin-orders.php?order_status=pending&order_id=<?php echo $order['order_id'] ?>"
                                                    <?php if (isset($order['status']) && $order['status'] === 'pending') echo 'selected disabled' ?>>
                                                    Pending
                                                </option>
                                                <option
                                                    value="admin-orders.php?order_status=preparing&order_id=<?php echo $order['order_id'] ?>"
                                                    <?php if (isset($order['status']) && $order['status'] === 'preparing') echo 'selected disabled' ?>>
                                                    Preparing
                                                </option>
                                                <option
                                                    value="admin-orders.php?order_status=completed&order_id=<?php echo $order['order_id'] ?>"
                                                    <?php if (isset($order['status']) && $order['status'] === 'completed') echo 'selected disabled' ?>>
                                                    Completed</option>
                                                <option
                                                    value="admin-orders.php?order_status=cancelled&order_id=<?php echo $order['order_id'] ?>"
                                                    <?php if (isset($order['status']) && $order['status'] === 'cancelled') echo 'selected disabled' ?>>
                                                    Cancelled</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="payment_status" id="payment_status"
                                                onchange="window.location=this.value">
                                                <option
                                                    value="admin-orders.php?payment_status=pending&order_id=<?php echo $order['order_id'] ?>"
                                                    <?php if (isset($order['payment_status']) && $order['payment_status'] === 'pending') echo 'selected disabled' ?>>
                                                    Pending</option>
                                                <option
                                                    value="admin-orders.php?payment_status=paid&order_id=<?php echo $order['order_id'] ?>"
                                                    <?php if (isset($order['payment_status']) && $order['payment_status'] === 'paid') echo 'selected disabled' ?>>
                                                    Paid</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="actions" id="order-actions"
                                                onchange="window.location=this.value">
                                                <option value="view" disabled aria-disabled="true">View</option>
                                                <option value="edit" disabled aria-disabled="true">Edit</option>
                                                <option value="admin-orders.php?order_action=delete&order_id=<?php echo $order['order_id'] ?>">Delete</option>
                                            </select>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="message">No orders yet</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <script async src="./scripts/dashboard.js"></script>
</body>

</html>