<?php
require_once "../components/restriction_admin.php";
require_once "../components/db_connect.php";
require_once "../components/navbar.php";
require_once "../components/footer.php";
require_once "../components/links.php";

// ============================
// Admin Info
// ============================
$sqlAdmin = "SELECT * FROM users WHERE user_id = {$_SESSION['admin']}";
$resultAdmin = mysqli_query($connect, $sqlAdmin);
$admin = mysqli_fetch_assoc($resultAdmin);
// ============================
// Product Search
// ============================
$search = $_GET['search'] ?? '';
if ($search !== '') {
    $sqlProducts = "SELECT * FROM products
                    WHERE name LIKE '%$search%' OR description LIKE '%$search%'";
} else {
    $sqlProducts = "SELECT * FROM products";
}
$resultProducts = mysqli_query($connect, $sqlProducts);

$productLayout = "";
if (mysqli_num_rows($resultProducts) > 0) {
    $products = mysqli_fetch_all($resultProducts, MYSQLI_ASSOC);
    foreach ($products as $prod) {
        // So Discount only shows if there is a Discount:
        $discountText = "";
        if (!is_null($prod["discount"]) && $prod["discount"] != "") {
            $discountText = "
        <p class='card-text my-0'>
            Discount: <b class='text-warning'>{$prod['discount']}%</b>
        </p>
    ";
        }
        $productLayout .= "
        <div class='col mb-4'>
            <div class='card h-100'>
                <img src='../pictures/products/{$prod['product_picture']}' class='card-img-top' alt='{$prod['name']}'>
                <div class='card-body'>
                    <h5 class='card-title text-success'>{$prod['name']}</h5>
                    <p class='card-text my-0'>Strength: {$prod['strength']}</p>
                    <p class='card-text my-0'>Aroma: {$prod['aroma']}</p>
                    <p class='card-text my-0'>Price: {$prod['price']} €</p>
                    $discountText
                    <a href='details.php?id={$prod['product_id']}' class='btn btn-success my-2'>Details</a>
                    <a href='update.php?id={$prod['product_id']}' class='btn btn-secondary'>Edit</a>
                    <a href='delete.php?id={$prod['product_id']}' class='btn btn-danger'>Delete</a>
                </div>
            </div>
        </div>
        ";
    }
} else {
    $productLayout = "<p>No products found.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <?= $links ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <?= $navbar ?>

    <div class="container mt-4">

        <h1>Product Management</h1>

        <!-- Search Form -->
        <form method="get" class="d-flex gap-2 mb-3">
            <input type="text" name="search" placeholder="Product search..." class="form-control" value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-success">Search</button>
            <a href="dashboard.php" class="btn btn-secondary">X</a>
        </form>

        <!-- Create Product Button -->
        <a href="../products/create.php" class="btn btn-success my-3">Create Product</a>

        <!-- Product Overview -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
            <?= $productLayout ?>
        </div>
    </div>

    <!-- Footer -->
    <?= $footer ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>