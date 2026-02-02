<?php
require_once "components/restriction_user.php";
require_once "components/db_connect.php";
require_once "components/navbar.php";
require_once "components/footer.php";
require_once "components/links.php";

$userId = $_SESSION["user"];
$sql = "SELECT * FROM users WHERE user_id = $userId";
$result = mysqli_query($connect, $sql);
$row = mysqli_fetch_assoc($result);

//===================
// Product query with supplier_id
//===================
$searchFilter = '';
if (isset($_GET['search']) && $_GET['search'] !== '') {
    $search = mysqli_real_escape_string($connect, $_GET['search']);
    $searchFilter = " AND (name LIKE '%$search%' OR description LIKE '%$search%')";
}

// Latin America
$sqlLatin = "SELECT * FROM products WHERE supplier_id IN (1,3) $searchFilter";
$resultLatin = mysqli_query($connect, $sqlLatin);

// Africa
$sqlAfrica = "SELECT * FROM products WHERE supplier_id = 2 $searchFilter";
$resultAfrica = mysqli_query($connect, $sqlAfrica);

// Asia
$sqlAsia = "SELECT * FROM products WHERE supplier_id = 4 $searchFilter";
$resultAsia = mysqli_query($connect, $sqlAsia);

// For Future: other suppliers
$sqlOther = "SELECT * FROM products WHERE supplier_id NOT IN (1,2,3,4) $searchFilter";
$resultOther = mysqli_query($connect, $sqlOther);

// Show Products
function renderProducts($result)
{
    $layout = "";
    if (mysqli_num_rows($result) > 0) {
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        foreach ($rows as $rowP) {
            $layout .= "
            <div class='col mb-3'>
                <div class='card h-100'>
                    <img src='pictures/products/{$rowP["product_picture"]}' class='card-img-top' alt='{$rowP["name"]}'>
                    <div class='card-body'>
                        <h5 class='card-title'>{$rowP["name"]}</h5>
                        <p class='card-text my-0'>Strength: {$rowP["strength"]}</p>
                        <p class='card-text my-0'>Aroma: {$rowP["aroma"]}</p>
                        <p class='card-text my-0'><b>Price: {$rowP["price"]}€</b></p>
                        <hr>
<div class='d-flex gap-2 justify-content-between'>
    <a href='details.php?id={$rowP['product_id']}' class='btn btn-primary'>Details</a>

    <form action='cart/add_to_cart.php' method='post' class='m-0'>
        <input type='hidden' name='product_id' value='{$rowP['product_id']}'>
                <button type='submit' class='btn btn-success'>
                Add to Cart
            <i class='fa-solid fa-cart-shopping me-1'></i>
        </button>
    </form>
</div>
                    </div>
                </div>
            </div>
            ";
        }
    } else {
        $layout = "<p>No products found in this section.</p>";
    }
    return $layout;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Welcome <?= $row["first_name"]; ?></title>
    <?= $links ?>
</head>

<body>
    <?= $navbar ?>
    <div class="d-flex align-items-center justify-content-between flex-wrap mx-3">
        <div class="d-flex align-items-center gap-2">
            <img src="pictures/user/<?= $row["user_picture"]; ?>"
                style="width:75px; height:75px; object-fit:cover;"
                alt="<?= $row["first_name"]; ?> <?= $row["last_name"]; ?>">
            <h4 class="mb-0">Welcome <?= $row["first_name"]; ?> <?= $row["last_name"]; ?></h4>
        </div>

        <form method="get" action="userprofile.php" class="d-flex gap-2">
            <input type="text" name="search" placeholder="Product search..."
                class="form-control"
                value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <button type="submit" class="btn btn-success">Search</button>
            <a href="userprofile.php" class="btn btn-secondary">X</a>
        </form>
    </div>
    <div class="container my-3">
        <h1>Explore our tasty coffee blends!</h1>

        <h2>Latin America</h2>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
            <?= renderProducts($resultLatin) ?>
        </div>
        <hr>

        <h2>Africa</h2>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
            <?= renderProducts($resultAfrica) ?>
        </div>
        <hr>

        <h2>Asia</h2>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
            <?= renderProducts($resultAsia) ?>
        </div>
        <!--   <hr>
        FOR FUTURE USE
        <h3 class="mt-4">Other Suppliers</h3>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
            <?= renderProducts($resultOther) ?>
        </div> -->

    </div>

    <?= $footer ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>