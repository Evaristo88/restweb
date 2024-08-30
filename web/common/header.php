<?php

declare(strict_types=1);
require_once './server/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('Location: /login.php');
    exit;
}
?>
<header>
    <a href="/">
        <img class="rudy-logo" src="/images/LOGO.jpg" alt="logo">
    </a>
    <nav class="topnav">
        <a class="active" href="/">Home</a>
        <a href="/popular-dishes.php">Popular</a>
        <a href="/about.php">About</a>
        <a href="/menu.php">Menu</a>
        <a href="/drinks.php">Drinks</a>
        <?php if (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['role']) && $_SESSION['role'] === 'customer'): ?>
            <a href="/my-orders.php">My Orders</a>
        <?php endif; ?>
        <a href="/order.php">Order</a>
    </nav>
    <div class="nav-user">
        <div class="icons">
            <i class="fas fa-bars" id="menu-bars"></i>
            <a href="/cart.php" class="fas fa-shopping-cart"></a>
        </div>
        <?php if (isset($_SESSION['user_id'])): ?>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="/dashboard.php" id="login">Dashboard</a>
            <?php endif; ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <input type="submit" id="register" name="logout" value="logout" />
            </form>
        <?php else: ?>
            <a href="/login.php" id="login">Login</a>
            <a href="/register.php" id="register">Register</a>
        <?php endif; ?>
    </div>
</header>

<!-- search section -->
<form action="" id="search-form">
    <input type="search" placeholder="Search here..." name="" id="search-box">
    <label for="search-box" class="fas fa-search"></label>
    <i class="fas fa-times" id="close"></i>
</form>