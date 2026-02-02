<?php

session_start();
if (!isset($_SESSION["admin"]) && !isset($_SESSION["user"])) {
    header("Location: login.php?restricted=true"); //send the query string to the login page to show the error message to the users who are restrivted to access this page
    exit;
}

require_once "../components/db_connect.php";
require_once "../components/navbar.php";
require_once "../components/footer.php";
require_once "../components/links.php";

// For the line underneath the navbar
$sql = "SELECT * FROM users WHERE user_id = $_SESSION[admin]";
$result = mysqli_query($connect, $sql);
$row = mysqli_fetch_assoc($result);

//===================
//loading suppliers
//===================
$sqlSupplier = "SELECT * FROM supplier";
$resultSupplier = mysqli_query($connect, $sqlSupplier); // Go button

$layout = "";

// Create the suppliers
if (mysqli_num_rows($resultSupplier) > 0) {
    $rowsSupplier = mysqli_fetch_all($resultSupplier, MYSQLI_ASSOC);

    foreach ($rowsSupplier as $rowS) {
        $layout .= "
            <div class='card my-3'>
                <div class='card-body'>
                <img src='../pictures/supplier/{$rowS["supplier_picture"]}' class='card-img-top' alt='{$rowS["name"]}' style='max-width: 800px; height: auto; border-radius: 8px;'>
                    <div class='card-body'>
                        <h5 class='card-title text-success'>{$rowS["name"]}</h5>
                        <p class='card-text'>Location: {$rowS["location"]}</p>
                        <p class='card-text'>{$rowS["story"]}</p>
                        <a href='update.php?id={$rowS["supplier_id"]}' class='btn btn-secondary'>Edit</a>
                        <a href='delete.php?id={$rowS["supplier_id"]}' class='btn btn-danger'>Delete</a>
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
    <!-- Bootstrap & CSS -->
    <?= $links ?>

    <title>Suppliers</title>
</head>

<body>

    <!-- Navbar -->
    <?= $navbar ?>
    <!-- Line beneath -->

    <div class="container">
        <h1>Supplier Management</h1>

        <!-- Create-Button -->
        <a class="btn btn-success my-3" href="create.php">Create a supplier</a>
        <!-- The cards -->
        <div class="container">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
                <?= $layout ?>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <?= $footer ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>