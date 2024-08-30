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
    header('location:dashboard.php');
}

// Change order payment status
if (isset($_GET['payment_status']) && isset($_GET['order_id'])) {
    $payment_status = $_GET['payment_status'];
    $query_update_payment_status = "UPDATE orders SET payment_status = ? WHERE id = ?";
    $update_payment_status = $connection->prepare($query_update_payment_status);
    $update_payment_status->execute([$payment_status, $_GET['order_id']]);
    $update_payment_status->close();
    header('location:dashboard.php');
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
    header('location:dashboard.php');
}

// Fetch top 10 recent customers
$query_recent_customers = "
SELECT `first_name` FROM `users`
WHERE `role` = 'customer'
ORDER BY `created_at` DESC
LIMIT 10;";
$query = $connection->prepare($query_recent_customers);
$query->execute();
$query->store_result();
$query->bind_result($first_name);

// Store results
$recent_customers = [];
while ($query->fetch()) {
    $recent_customers[] = ['first_name' => $first_name];
}
if (empty($recent_customers)) {
    $message = "No customers yet";
}
$query->close();

// Fetch 10 recent orders
$query_customer_orders = "
SELECT o.id, o.total_amount, o.order_date, o.status, o.payment_method, o.payment_status, u.first_name
FROM orders AS o
JOIN users AS u
ON o.user_id = u.id
ORDER BY order_date DESC
LIMIT 10;";
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

<div class="details">
    <div class="recentOrders">
        <div class="cardHeader">
            <h2>Recent Orders</h2>
            <a href="/admin-orders.php" class="btnDetails">View All</a>
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
                                        value="dashboard.php?order_status=pending&order_id=<?php echo $order['order_id'] ?>"
                                        <?php if (isset($order['status']) && $order['status'] === 'pending') echo 'selected disabled' ?>>
                                        Pending
                                    </option>
                                    <option
                                        value="dashboard.php?order_status=preparing&order_id=<?php echo $order['order_id'] ?>"
                                        <?php if (isset($order['status']) && $order['status'] === 'preparing') echo 'selected disabled' ?>>
                                        Preparing
                                    </option>
                                    <option
                                        value="dashboard.php?order_status=completed&order_id=<?php echo $order['order_id'] ?>"
                                        <?php if (isset($order['status']) && $order['status'] === 'completed') echo 'selected disabled' ?>>
                                        Completed</option>
                                    <option
                                        value="dashboard.php?order_status=cancelled&order_id=<?php echo $order['order_id'] ?>"
                                        <?php if (isset($order['status']) && $order['status'] === 'cancelled') echo 'selected disabled' ?>>
                                        Cancelled</option>
                                </select>
                            </td>
                            <td>
                                <select name="payment_status" id="payment_status"
                                    onchange="window.location=this.value">
                                    <option
                                        value="dashboard.php?payment_status=pending&order_id=<?php echo $order['order_id'] ?>"
                                        <?php if (isset($order['payment_status']) && $order['payment_status'] === 'pending') echo 'selected disabled' ?>>
                                        Pending</option>
                                    <option
                                        value="dashboard.php?payment_status=paid&order_id=<?php echo $order['order_id'] ?>"
                                        <?php if (isset($order['payment_status']) && $order['payment_status'] === 'paid') echo 'selected disabled' ?>>
                                        Paid</option>
                                </select>
                            </td>
                            <td>
                                <select name="actions" id="order-actions"
                                    onchange="window.location=this.value">
                                    <option value="view" disabled aria-disabled="true">View</option>
                                    <option value="edit" disabled aria-disabled="true">Edit</option>
                                    <option value="dashboard.php?order_action=delete&order_id=<?php echo $order['order_id'] ?>">Delete</option>
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
    <div class="recentCustomers">
        <div class="cardHeader">
            <h2>Recent Customers</h2>
        </div>
        <?php if (!empty($recent_customers)): ?>
            <table>
                <tbody>
                    <?php foreach ($recent_customers as $customer): ?>
                        <tr>
                            <td width="60px">
                                <div class="imgBox">
                                    <img src="./images/take_control.jpg" alt="" />
                                </div>
                            </td>
                            <td>
                                <h4><?= $customer['first_name'] ?><br /><span>Nairobi</span></h4>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="message">No customers yet</p>
        <?php endif; ?>
    </div>
</div>