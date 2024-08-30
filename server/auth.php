<?php

function auth_admin()
{
    if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
        // Allow only admins. We redirect to 404(Not Found) and not 403 (Unauthorized) because we do not want to
        // give hackers a hint about the presence of protected pages.
        header('Location: /404.php');
        exit;
    }
    return true;
}
function auth_customer()
{
    if (isset($_SESSION['role']) && $_SESSION['role'] !== 'customer') {
        // Allow only customers. We redirect to 404(Not Found) and not 403 (Unauthorized) because we do not want to
        // give hackers a hint about the presence of protected pages.
        header('Location: /404.php');
        exit;
    }
    return true;
}
