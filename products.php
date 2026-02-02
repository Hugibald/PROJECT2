<?php
session_start();
require_once "components/db_connect.php";
require_once "components/navbar.php";
require_once "components/footer.php";
require_once "components/links.php";


if (isset($_GET['search']) && $_GET['search'] !== '') {
    $search = $_GET['search'];
    $sql = "SELECT * FROM products
        WHERE name LIKE '%$search%' OR description LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM products";
}

$result = mysqli_query($connect, $sql); // Go button

$layout = "";

if (mysqli_num_rows($result) > 0) {
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

    foreach ($rows as $row) {
        // So details-Buttons only shown when loged in!
        $detailsButton = "<a href='details.php?id={$row["product_id"]}' class='btn btn-primary'>Details</a>";
        if (!isset($_SESSION["admin"]) && !isset($_SESSION["user"])) {
            $detailsButton = "";
        }
        // So Add to Cart only visible when user
        $cartButton = "
            <form action='cart/add_to_cart.php' method='post'>
                <input type='hidden' name='product_id' value='{$row["product_id"]}'>
                <button type='submit' class='btn btn-success'>
                Add to Cart
            <i class='fa-solid fa-cart-shopping me-1'></i>
        </button>            
        </form>";
        if (!isset($_SESSION["user"])) {
            $cartButton = "";
        }
        // So Discount only shows if there is a Discount:
        $discountText = "";
        if (!is_null($row["discount"]) && $row["discount"] != "") {
            $discountText = "<p class='card-text my-0'>Discount: {$row["discount"]}</p>";
        }

        $layout .= "
        <div>
            <div class='card my-3'>
                <img src='pictures/products/{$row["product_picture"]}' class='card-img-top' alt='{$row["name"]}'>
                <div class='card-body'>
                    <h5 class='card-title text-success'>{$row["name"]}</h5>
                    <p class='card-text my-0'>Strength: {$row["strength"]}</p>
                    <p class='card-text my-0'>Aroma: {$row["aroma"]}</p>
                    <p class='card-text my-0'><b>Price: {$row["price"]}€</b></p>
                    $discountText
                    <div class='d-flex gap-2 justify-content-between mt-3'>

                    $detailsButton
                    $cartButton
                </div>
                </div>
            </div>
        </div>
    ";
    }
} else {
    $layout = "<h3>No Data found</h3>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= $links ?>

    <title>Beanternet</title>
</head>

<body>
    <?= $navbar ?>

    <div class="container">
        <h1>Explore our coffee blends</h1>
        <div class="my-3 d-flex align-items-center justify-content-between">
            <form method="get" action="products.php" class="d-flex gap-2">
                <input type="text" name="search" placeholder="Product search..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                <button type="submit" class="btn btn-success">Search</button>
                <a href="products.php" class="btn btn-secondary">X</a>
            </form>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
            <?= $layout ?>
        </div>
    </div>
    <?= $footer ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>