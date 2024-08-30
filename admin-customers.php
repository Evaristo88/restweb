<?php

declare(strict_types=1);
require_once './server/session.php';
require_once './server/db.php';
require_once './server/auth.php';
auth_admin();

$connection = connectToDatabase();
$sql = "SELECT first_name, last_name, email, role FROM users WHERE role = 'customer' LIMIT 10;";

// Query for customers
$results = mysqli_query($connection, $sql);
if (mysqli_num_rows($results) <= 0) {
    $message = "No customers yet";
} else {
    $customers = mysqli_fetch_all($results, MYSQLI_ASSOC);
}
mysqli_close($connection);
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
                        <h2>All Customers</h2>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <td>First Name</td>
                                <td>Last Name</td>
                                <td>Email</td>
                                <td>Role</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($customers) : ?>
                                <?php foreach ($customers as $customer) : ?>
                                    <tr>
                                        <td><?= $customer['first_name'] ?></td>
                                        <td><?= $customer['last_name'] ?></td>
                                        <td><?= $customer['email'] ?></td>
                                        <td><?= $customer['role'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php elseif ($message) : ?>
                                <tr>
                                    <td colspan="4"><?= $message ?></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script async src="./scripts/dashboard.js"></script>
</body>

</html>