<?php

declare(strict_types=1);
require_once './server/session.php';
require_once './server/db.php';
require_once './server/auth.php';
auth_admin();

$connection = connectToDatabase();
$sql = "
SELECT m.title, m.description, m.price, m.featured, c.title AS category_title
FROM menu_items AS m
JOIN categories AS c ON m.category_id = c.id
LIMIT 10;
";
// Query the menu items
$results = mysqli_query($connection, $sql);
if (mysqli_num_rows($results) <= 0) {
    $message = "Menu is empty. Add some products";
} else {
    $menu_item = mysqli_fetch_all($results, MYSQLI_ASSOC);
}
mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Restweb: Admin</title>
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
                        <h2>Menu</h2>
                        <a href="/admin-add-menu-item.php" class="btnDetails">Add menu item</a>
                    </div>
                    <?php if ($menu_item) : ?>
                        <table>
                            <thead>
                                <tr>
                                    <td>Title</td>
                                    <td>Description</td>
                                    <td>Category</td>
                                    <td>Price</td>
                                    <td>Featured</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($menu_item as $item) : ?>
                                    <tr>
                                        <td><?= $item['title'] ?></td>
                                        <td><?= substr($item['description'], 0, 30) . "..." ?></td>
                                        <td><?= $item['category_title'] ?></td>
                                        <td><?= $item['price'] ?></td>
                                        <?php if ($item['featured'] == 0) : ?>
                                            <td>No</td>
                                        <?php else : ?>
                                            <td>Yes</td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p><?= $message ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <script async src="./scripts/dashboard.js"></script>
</body>

</html>