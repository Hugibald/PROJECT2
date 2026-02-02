<?php

session_start();
require_once "components/db_connect.php";
require_once "components/navbar.php";
require_once "components/footer.php";
require_once "components/links.php";

// Admin edit Index Button
$editIndex = "
        <a href='edit_index.php' class='btn btn-primary my-2'>Edit Index</a>";
if (!isset($_SESSION["admin"])) {
    $editIndex = "";
}
// Call hero.json
$hero = json_decode(file_get_contents('JSON/hero.json'), true);
$indexProducts = json_decode(file_get_contents('JSON/display_products.json'), true)['product_ids'] ?? [];

// Call displayProducts.json
$productIds = implode(',', $indexProducts ?: [1, 4, 7, 10]); // Default fallback
$sql = "SELECT * FROM products WHERE product_id IN ($productIds)";
$result = mysqli_query($connect, $sql);

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
        $layout .= "
        <div>
            <div class='card my-3'>
                <img src='pictures/products/{$row["product_picture"]}' class='card-img-top' alt='{$row["name"]}'>
                <div class='card-body'>
                    <h5 class='card-title text-success'>{$row["name"]}</h5>
                    <p class='card-text my-0'>Strength: {$row["strength"]}</p>
                    <p class='card-text my-0'>Aroma: {$row["aroma"]}</p>
                    <p class='card-text my-0'><b>Price: {$row["price"]}€</b></p>
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
    <!-- Admin Index edit button -->
    <?= $editIndex ?>

    <section class="hero">
        <img src="pictures/<?= $hero['image'] ?>" alt="Hero Image" class="hero-image">


        <div class="hero-content rounded">
            <h1><?= $hero['title'] ?></h1>
            <p><?= $hero['subtitle'] ?></p>
            <small><?= $hero['small_text'] ?></small><br>
            <a href="userprofile.php" class="btn btn-lg btn-success mt-2">Shop Now</a>
        </div>
    </section>

    <!-- BESTSELLERS SECTION-->
    <div class="container">
        <h2 class="pt-5">Bestseller</h2>
        <div class="container bestseller"></div>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4">
            <?= $layout ?>
        </div>
    </div>

    </div>
    <!-- BESTSELLERS SECTION ENDS-->

    <!-- ORIGIN SECTION -->
    <section class="coffee-origin">
        <div class="coffee-origin-overlay"></div>

        <div class="coffee-origin-content">
            <h2>Origin of Our Coffees</h2>
            <p>
                We currently source our coffee from four farms located across different regions
                of the world. Each origin brings its own unique flavor profile, shaped by
                altitude, climate, and terroir. Here you can learn more about the farms
                we partner with.
            </p>
            <a href="about.php" class="btn btn-lg btn-success">About Us</a>
        </div>
    </section>
    <!--ORIGIN SECTION -->


    <!-- WHY US SECTION START-->
    <section class="why-section">
        <div class="container">
            <h2>Why choose us?</h2>

            <div class="why-grid">

                <div class="why-item">
                    <div class="why-icon">
                        <i class="fa-solid fa-people-carry-box"></i>
                    </div>
                    <h3>Fast Delivery</h3>
                    <p>Receive your order quickly with reliable and efficient delivery.</p>
                </div>

                <div class="why-item">
                    <div class="why-icon">
                        <i class="fas fa-coffee"></i>
                    </div>
                    <h3>Wide Selection</h3>
                    <p>Choose from a large variety of premium coffee beans.</p>
                </div>

                <div class="why-item">
                    <div class="why-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h3>Free Shipping</h3>
                    <p>Enjoy free shipping during the holiday season.</p>
                </div>

                <div class="why-item">
                    <div class="why-icon">
                        <i class="fas fa-seedling"></i>
                    </div>
                    <h3>Organically Sourced</h3>
                    <p>
                        We partner directly with coffee farmers, ensuring fair support and sustainably grown, high-quality coffee beans.
                    </p>
                </div>

            </div>
        </div>
    </section>
    <!-- WHY US SECTION END-->



    <?= $footer ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>