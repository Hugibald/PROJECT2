<?php
require_once "../components/restriction_admin.php";
require_once "../components/db_connect.php";
require_once "../components/subfolder_links.php";

// More secure way of get id
$id = (int) $_GET['id'];
$sql = "SELECT * FROM `supplier` WHERE supplier_id = $id";
$result = mysqli_query($connect, $sql);
$row = mysqli_fetch_assoc($result);
if (!$row) {
    die("Supplier not found");
}

// echo "<pre>";
// print_r($row);
// echo "</pre>";

// Unlink only if not default-picture
if ($row['supplier_picture'] !== 'supplier.jpg') {
    $file = "../pictures/supplier/" . $row['supplier_picture'];
    if (is_file($file)) {
        unlink($file);
    }
}

// Delete row
$deleteSql = "DELETE * FROM `supplier` WHERE supplier_id = $id";
$result = mysqli_query($connect, $deleteSql);

    if($result){
        $success_error_message = "The supplier was deleted.";
    } else{
        $success_error_message = "OOPs! Something Wrong";
    }
    header("refresh: 3; url=dashboard.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= $links ?>
    <title>Delete supplier</title>
</head>

<body>
    <?php
    include __DIR__ . '/../components/navbar.php';
    echo $navbar;
    ?>
    <div class="container  mb-3">
        <div class="row">
            <div class="col col-md-6">
                <h3 class="text py-2">Delete a supplier</h3>
                <div class="text fs-5 text-success"><?= $success_error_message?? ""; ?></div>
            </div>
        </div>
    </div>
    <?php
    include __DIR__ . '/../components/footer.php';
    echo $footer;
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>
