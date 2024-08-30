<?php

declare(strict_types=1);
require_once './server/session.php';
require_once './server/db.php';
require_once './server/auth.php';
require_once './utils.php';

auth_admin();

$title = $description = "";
$titleError = $descriptionError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "$title: $description: $price: $category: $featured";
    if (empty($_POST["title"])) {
        $titleError = "Title is required";
    } else {
        $title = sanitize_input($_POST["title"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $title)) {
            $titleError = "Only letters and white space allowed";
        }
    }
    if (empty($_POST["description"])) {
        $descriptionError = "Description is required";
    } else {
        $description = sanitize_input($_POST["description"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $description)) {
            $descriptionError = "Only letters and white space allowed";
        }
    }
    add_category($title, $description);
}
function add_category($title, $description)
{
    $connection = connectToDatabase();
    // Add prepared statements
    $stmt = $connection->prepare("INSERT INTO categories (title, description) VALUES (?, ?)");
    // bind values
    $stmt->bind_param("ss", $title, $description);
    // Save category
    $stmt->execute();
    // Close db connections when done
    $stmt->close();
    $connection->close();
    // Redirect to categories page to see added category
    header('Location: http://127.0.0.1/admin-categories.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Rudys: Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="styles/global.css" />
    <link rel="stylesheet" href="styles/dashboard.css" />
    <link rel="stylesheet" href="styles/dashboard-nav.css" />
    <link rel="stylesheet" href="styles/dashboard-top-nav.css" />
    <link rel="stylesheet" href="styles/admin-add-menu-item.css" />
</head>

<body>
    <div class="container">
        <?php require './dashboard-nav.php' ?>
        <div class="main">
            <?php require './dashboard-top-nav.php' ?>
            <div class="menu">
                <div class="add-product">
                    <div class="cardHeader">
                        <h2>Add Category</h2>
                        <!-- <a href="/admin-add-menu-item.php" class="btnDetails">Save</a> -->
                    </div>
                    <div>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <span class="error">* required fields</span>
                            <div>
                                <label for="title">Title</label><span class="error">*</span>
                                <br />
                                <input type="text" name="title" id="title" value="<?php echo $title ?>" required aria-required />
                                <br />
                                <span class="error"><?php echo $titleError; ?></span>
                            </div>
                            <div>
                                <label for="description">Description</label><span class="error">*</span>
                                <br />
                                <textarea maxlength="256" cols="30" rows="5" name="description" id="description" required aria-required value="<?php echo $description ?>">
                                </textarea>
                                <br />
                                <span class="error"><?php echo $descriptionError; ?></span>
                            </div>
                            <div>
                                <input type="submit" value="Add Category" name="add">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script async src="./scripts/dashboard.js"></script>
</body>

</html>