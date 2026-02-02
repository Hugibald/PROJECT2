<?php
session_start();
require_once "components/db_connect.php";
require_once "components/navbar.php";
require_once "components/footer.php";
require_once "components/links.php";

$supplier_id = $_GET['id'];


$sql = "SELECT * FROM `supplier` WHERE supplier_id = $supplier_id";
$result = mysqli_query($connect, $sql);

$layout = "";

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    $layout = "
            <div class='card my-3'>
                <div class='card-body'>
                <img src='pictures/supplier/{$row["supplier_picture"]}' class='card-img-top' alt='{$row["name"]}' style='max-width: 800px; height: auto; border-radius: 8px;'>
                    <div class='card-body'>
                        <h5 class='card-title text-success'>{$row["name"]}</h5>
                        <p class='card-text my-0'>Location: {$row["location"]}</p>
                        <p class='card-text my-0'>{$row["story"]}</p>
                        <a href='about.php' class='btn btn-secondary my-2'>Back</a>
                    </div>
                </div>
            </div>
    ";
} else {
    $layout = "<h3>No supplier found with ID $supplier_id </h3>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= $links ?>

    <title><?= $row["supplier_name"];?></title>
</head>
<body>
    <?= $navbar ?>
    <div class="container ">
        <div class="row justify-content-center">
            <div>
                <?php echo $layout ?>
            </div>
        </div>
    </div>
    <?= $footer ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
