<?php
require_once './server/session.php';
require_once './server/auth.php';
auth_admin();
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
    <link rel="stylesheet" href="styles/dashboard-stats.css" />
    <link rel="stylesheet" href="styles/dashboard-details.css" />
</head>

<body>
    <div class="container">
        <?php require './dashboard-nav.php' ?>
        <div class="main">
            <?php require './dashboard-top-nav.php' ?>
            <?php require './dashboard-stats.php' ?>
            <?php require './dashboard-details.php' ?>
        </div>
    </div>
    <script async src="./scripts/dashboard.js"></script>
</body>

</html>