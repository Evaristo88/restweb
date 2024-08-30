<?php

declare(strict_types=1);
require_once './server/session.php';
require_once './server/auth.php';
require_once './server/db.php';
require_once './utils.php';

auth_admin();

// Connect to database
$connection = connectToDatabase();

$title = $description = $price = $category = "";
$featured = 0;
$titleError = $descriptionError = $priceError = $categoryError = $featuredError = "";

// Fetch all categories to display in category dropdown
$all_categories_query = "SELECT title FROM categories;";
$results = mysqli_query($connection, $all_categories_query);
$all_categories = mysqli_fetch_all($results, MYSQLI_ASSOC);

function validate_menu_inputs(string $title, string $description, string $price, string $category, string $featured)
{
    $errors = [];
    // validate title
    if (empty($title)) {
        $errors['title'] = "Title is required";
    } else {
        $title = sanitize_input($title);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $title)) {
            $errors['title'] = "Only letters and white space allowed";
        }
    }
    // validate description
    if (empty($description)) {
        $errors['description'] = "Description is required";
    } else {
        $description = sanitize_input($description);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $description)) {
            $errors['description'] = "Only letters and white space allowed";
        }
    }
    if (empty($category)) {
        $errors['category']  = "Category is required";
    } else {
        $category = sanitize_input($category);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $category)) {
            $errors['category'] = "Only letters and white space allowed";
        }
    }
    if (empty($price)) {
        $errors['price'] = "Price is required";
    } else {
        $price = sanitize_input($price);
        if (!preg_match("/^[0-9]*$/", $price)) {
            $errors['price'] = "Only numbers are allowed";
        }
    }
    if (empty($featured)) {
        $errors['featured'] = "Description is required";
    } else {
        $featured = sanitize_input($featured);
        if (!is_numeric($featured)) {
            $errors['featured'] = "Can only be yes or no";
        }
    }
    return $errors;
}

$errors = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = validate_menu_inputs(
        $title = $_POST['title'],
        $description = $_POST['description'],
        $price = $_POST['price'],
        $category = $_POST['category'],
        $featured = $_POST['featured'],
    );
    if (empty($errors)) {
        add_menu_item($title, $description, $price, $category, (int) $featured);
    }
}
function add_menu_item($title, $description, $price, $category, $featured)
{
    global $connection;
    $stmt = $connection->prepare("SELECT id FROM categories WHERE title LIKE ?");
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $fetched_category = $stmt->get_result();
    while ($row = $fetched_category->fetch_assoc()) {
        $category = $row['id'];
    }
    // Add prepared statements
    $stmt = $connection->prepare("INSERT INTO menu_items (title, description, price, category_id, featured) VALUES (?, ?, ?, ?, ?)");
    // bind values
    $stmt->bind_param("ssssi", $title, $description, $price, $category, $featured);
    // Save menu item
    $stmt->execute();
    // Close db connections when done
    $stmt->close();
    $connection->close();
    // Redirect to menu page to see added product
    header('Location: http://127.0.0.1/admin-menu.php');
    exit;
}
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
                        <h2>Add new menu item</h2>
                        <a href="/admin-add-menu-item.php" class="btnDetails">Save</a>
                    </div>
                    <div>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <span class="error">* required fields</span>
                            <div>
                                <label for="title">Title</label><span class="error">*</span>
                                <br />
                                <input type="text" name="title" id="title" value="<?php echo $title ?>" required aria-required />
                                <br />
                                <span class="error">
                                    <?php if (!empty($errors) && array_key_exists('title', $errors)) echo $errors['title']; ?>
                                </span>
                            </div>
                            <div>
                                <label for="description">Description</label><span class="error">*</span>
                                <br />
                                <textarea maxlength="256" cols="30" rows="5" name="description" id="description" required aria-required value="<?php echo $description ?>">
                                </textarea>
                                <br />
                                <span class="error"><?php if (!empty($errors) && array_key_exists('description', $errors)) echo $errors['description']; ?></span>
                            </div>
                            <div>
                                <label for="category">Category</label>
                                <span class="error">*</span>
                                <br />
                                <select name="category" id="category" required>
                                    <option value="" selected>Select a category</option>
                                    <?php if ($all_categories) : ?>
                                        <?php foreach ($all_categories as $category) : ?>
                                            <option value="<?php echo $category['title'] ?>"><?php echo $category['title'] ?></option>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <option value="">No categories found</option>
                                    <?php endif; ?>
                                </select>
                                <br />
                                <span class="error"><?php if (!empty($errors) && array_key_exists('category', $errors)) echo $errors['category']; ?></span>
                                <br />
                            </div>
                            <div>
                                <label for="price">Price</label><span class="error">*</span>
                                <br />
                                Ksh <input type="text" name="price" id="price" value="<?php echo $price ?>" required aria-required />
                                <br />
                                <span class="error"><?php if (!empty($errors) && array_key_exists('price', $errors)) echo $errors['price']; ?></span>
                            </div>
                            <div>
                                <label for="featured">Feature this product?</label><br />
                                <input type="radio" name="featured" <?php if (isset($featured) && $featured === 0) echo "checked"; ?> value="0" checked>No
                                <input type="radio" name="featured" <?php if (isset($featured) && $featured === 1) echo "checked"; ?> value="1">Yes
                                <span class="error"><?php if (!empty($errors) && array_key_exists('featured', $errors)) echo $errors['featured']; ?></span>
                                <br><br>
                            </div>
                            <div>
                                <input type="submit" value="Add" name="add">
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