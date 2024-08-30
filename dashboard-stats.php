<?php

declare(strict_types=1);
require_once './server/session.php';
require_once './server/db.php';
require_once './server/auth.php';
auth_admin();

$connection = connectToDatabase();

// Count all customers
$query_num_of_customers = "SELECT COUNT(*) AS num_customers FROM `users` WHERE `role` = 'customer';";
$query_statement = mysqli_prepare($connection, $query_num_of_customers);
$query_statement->execute();
$query_statement->bind_result($num_customers);
$query_statement->fetch();
$query_statement->close();

// Count all orders
$query_num_of_orders = "SELECT COUNT(*) AS num_orders FROM `orders`";
$query = $connection->prepare($query_num_of_orders);
$query->execute();
$query->store_result();
$query->bind_result($num_orders);
$query->fetch();
$query->close();

// COunt numner of sales with paid orders
$query_num_of_sales = "SELECT COUNT(*) AS num_sales FROM `orders` WHERE `payment_status` = 'paid';";
$query = $connection->prepare($query_num_of_sales);
$query->execute();
$query->store_result();
$query->bind_result($num_sales);
$query->fetch();
$query->close();

// Count all sales
$query_total_sales = "SELECT SUM(total_amount) AS total_sales FROM `orders` WHERE `payment_status` = 'paid';";
$query = $connection->prepare($query_total_sales);
$query->execute();
$query->store_result();
$query->bind_result($total_sales);
$query->fetch();
$query->close();

// Close database connection
mysqli_close($connection);

?>
<div class="cardBox">
    <div class="card">
        <div>
            <div class="numbers"><?= $num_customers ?></div>
            <div class="cardName">Total customers</div>
        </div>
        <div class="iconBox">
            <i class="fa fa-user" aria-hidden="true"></i>
        </div>
    </div>
    <div class="card">
        <div>
            <div class="numbers"><?php if (isset($num_orders)) echo $num_sales  ?></div>
            <div class="cardName">Sale(s)</div>
        </div>
        <div class="iconBox">
            <i class="fa fa-shopping-cart" aria-hidden="true"></i>
        </div>
    </div>
    <div class="card">
        <div>
            <div class="numbers"><?php if (isset($num_orders)) echo $num_orders ?></div>
            <div class="cardName">Order(s)</div>
        </div>
        <div class="iconBox">
            <i class="fa fa-comment" aria-hidden="true"></i>
        </div>
    </div>
    <div class="card">
        <div>
            <div class="numbers">Tzs<?php if (isset($num_orders)) echo $total_sales  ?></div>
            <div class="cardName">Earnings</div>
        </div>
        <div class="iconBox">
            <i class="fa fa-usd" aria-hidden="true"></i>
        </div>
    </div>
</div>