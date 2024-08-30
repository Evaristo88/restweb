<?php

declare(strict_types=1);
require_once './server/session.php';
require_once './server/db.php';

$first_name = $last_name = $email = $password = "";
$first_nameError = $last_nameError = $emailError = $passwordError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["first_name"])) {
        $first_nameError = "First Name is required";
    } else {
        $first_name = test_input($_POST["first_name"]);
        if (is_numeric($first_name)) {
            $first_nameError = "Only letters and white space allowed";
        }
        // if (!preg_match("/^[a-zA-Z-' ]*$/", $first_name)) {
        //     $first_nameError = "Only letters and white space allowed";
        // }
    }
    if (empty($_POST["last_name"])) {
        $last_nameError = "Last Name is required";
    } else {
        $last_name = test_input($_POST["last_name"]);
        if (is_numeric($last_name)) {
            $last_nameError = "Only letters and white space allowed";
        }
        // if (!preg_match("/^[a-zA-Z-' ]*$/", $last_name)) {
        //     $last_nameError = "Only letters and white space allowed";
        // }
    }
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
    if (
        empty($first_nameError) &&
        empty($last_nameError) &&
        empty($emailError) &&
        empty($passwordError)
    ) {
        create_user($first_name, $last_name, $email, $password);
    }
}

function create_user($first_name, $last_name, $email, $password)
{
    // Connect to db
    $connection = connectToDatabase();
    // Prepare query to add new user
    $stmt = $connection->prepare("INSERT INTO users (first_name, last_name, email, password ) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $first_name, $last_name, $email, $password);
    $stmt->execute();
    $user_id = $stmt->insert_id;
    // Close connection after being done
    $stmt->close();
    // get user role
    $stmt = $connection->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($role);
    $stmt->fetch();
    $stmt->close();
    $connection->close();
    // Create user session
    $_SESSION['user_id'] = $user_id;
    $_SESSION['first_name'] = $first_name;
    $_SESSION['last_name'] = $last_name;
    $_SESSION['email'] = $email;
    $_SESSION['role'] = $role;
    // Redirect user to menu
    header('Location: http://127.0.0.1/menu.php');
    exit;
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
    <title>Rudy's: Create Account</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./styles/global.css">
    <link rel="stylesheet" href="./web/common/header.css">
    <link rel="stylesheet" href="./web/common/footer.css">
    <link rel="stylesheet" href="./styles/popular-dishes.css">
    <style>
        form {
            background-color: aquamarine;
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
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <h1>Registration form</h1>
        <span class="error">* required fields</span>
        <div>
            <label for="first_name">First Name</label><span class="error">*</span>
            <br />
            <input type="text" name="first_name" id="first_name" value="<?php echo $first_name ?>" required aria-required />
            <br />
            <span class="error"><?php echo $first_nameError; ?></span>
        </div>
        <div>
            <label for="last_name">Last Name</label><span class="error">*</span>
            <br />
            <input type="text" name="last_name" id="last_name" value="<?php echo $last_name ?>" required aria-required />
            <br />
            <span class="error"><?php echo $last_nameError; ?></span>
        </div>
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
            <input type="submit" name="submit" value="Create Account" />
        </div>
    </form>
    <?php require './web/common/footer.php' ?>
</body>

</html>