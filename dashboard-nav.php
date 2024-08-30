<?php

declare(strict_types=1);
require_once './server/session.php';
require_once './server/auth.php';
auth_admin();

if (isset($_GET['logout']) && $_GET['logout'] === 'logout') {
    session_unset();
    session_destroy();
    header('Location: /login.php');
    exit;
}
?>

<div class="navigation">
    <ul>
        <li>
            <a href="/">
                <span class="icon"><i class="fa fa-apple" aria-hidden="true"></i>
                </span>
                <span class="title">
                    <h2>Rudy's</h2>
                </span>
            </a>
        </li>
        <li>
            <a href="/dashboard.php">
                <span class="icon"><i class="fa fa-home" aria-hidden="true"></i>
                </span>
                <span class="title">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="/admin-customers.php">
                <span class="icon"><i class="fa fa-users" aria-hidden="true"></i>
                </span>
                <span class="title">Customers</span>
            </a>
        </li>

        <li>
            <a href="/admin-menu.php">
                <span class="icon"><i class="fa fa-comment" aria-hidden="true"></i>
                </span>
                <span class="title">Menu</span>
            </a>
        </li>

        <li>
            <a href="/admin-orders.php">
                <span class="icon"><i class="fa fa-lock" aria-hidden="true"></i>
                </span>
                <span class="title">Orders</span>
            </a>
        </li>
        <li>
            <a href="/admin-categories.php">
                <span class="icon"><i class="fa fa-lock" aria-hidden="true"></i>
                </span>
                <span class="title">Categories</span>
            </a>
        </li>
        <li>
            <a href="/dashboard.php?logout=logout">
                <span class="icon"><i class="fa fa-sign-out" aria-hidden="true"></i>
                </span>
                <span class="title">Sign Out</span>
            </a>
        </li>
    </ul>
</div>