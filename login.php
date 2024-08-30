<?php

declare(strict_types=1);
require './server/session.php';
require './server/db.php';

$email = $password = "";
$emailError = $passwordError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["email"])) {
        $emailError = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailError = "Invalid email format";
        }
    }
    if (empty($_POST["password"])) {
        $passwordError = "Password is required";
    } else {
        $password = test_input($_POST["password"]);
        // if () {
        //     $passwordError = "Passwor must be at least 8 characters long";
        // }
    }
    login_user($email, $password);
}

function login_user(string $email, string $pass)
{
    // Connect to db
    $connection = connectToDatabase();

    // prepare and bind
    $query = $connection->prepare("SELECT id, first_name, last_name, email, password, role FROM users WHERE email LIKE ?;");
    $query->bind_param("s", $email);
    $query->execute();
    $query->store_result();
    // Grab values from returned result if user is found

    $query->bind_result($id, $first_name, $last_name, $email, $password, $role);
    $query->fetch();

    if ($query->num_rows() === 0) {
        $GLOBALS['emailError'] = "Email not associated with an account. Please register.";
    } elseif ($pass !== $password) {
        $GLOBALS['passwordError'] = "Password is incorrect";
    } else {
        $_SESSION['user_id'] = $id;
        $_SESSION['first_name'] = $first_name;
        $_SESSION['last_name'] = $last_name;
        $_SESSION['email'] = $email;
        $_SESSION['role'] = $role;

        if ($_SESSION['role'] === 'admin') {
            header('Location: http://127.0.0.1/dashboard.php');
            exit;
        } else {
            header('Location: http://127.0.0.1/');
            exit;
        }
    }
    $query->close();
    $connection->close();
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rudy's: Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./styles/global.css">
    <link rel="stylesheet" href="./web/common/header.css">
    <link rel="stylesheet" href="./web/common/footer.css">
    <link rel="stylesheet" href="./styles/popular-dishes.css">
    <link rel="stylesheet" href="./styles/login.css">
    <style>
        form {
            background-image: url('images/imgg5.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 2rem;
            margin-top: 4rem;
        }

        .error {
            color: red;
        }
    </style>
</head>

<body>
    <?php require './web/common/header.php'; ?>
    <div class="login-form-bg">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <h1>Login</h1>
            <span class="error">* required fields</span>
            <div>
                <label for="email">Email</label><span class="error">*</span>
                <br />
                <input type="email" name="email" id="email" value="<?php echo $email ?>" required aria-required />
                <br />
                <span class="error"><?php echo $emailError; ?></span>
            </div>
            <div>
                <label for="password">Password</label><span class="error">*</span>
                <br />
                <input type="password" name="password" id="password" value="<?php echo $password ?>" required aria-required />
                <br />
                <span class="error"><?php echo $passwordError; ?></span>
            </div>
            <div>
                <input type="submit" value="Login" name="login">
            </div>
        </form>
    </div>
    <?php require './web/common/footer.php' ?>
</body>

</html>