<?php
require_once "../components/restriction_admin.php";
require_once "../components/db_connect.php";
require_once "../components/file_upload.php";
require_once "../components/cleaninputs.php";
// Link-component for subfolder:
require_once "../components/subfolder_links.php";
//===========
//getting products
//===========
$product_id = intval($_GET['id']);
$product_sql = "SELECT * FROM `products` WHERE product_id = $product_id";
$result = mysqli_query($connect, $product_sql);
$row = mysqli_fetch_assoc($result);
$name = $row['name'];
$description = $row['description'];
$strength = $row['strength'];
$aroma = $row['aroma'];
$price = $row['price'];
$discount = $row['discount'];
$supplier_id = $row['supplier_id'];

// $picture = $row['picture'];
// echo "<pre>";
// print_r($picture);
// echo "</pre>";

$sqlSuppliers = "SELECT supplier_id, name FROM supplier ORDER BY name ASC";
$resultuppliers = mysqli_query($connect, $sqlSuppliers);
$suppliers = mysqli_fetch_all($resultuppliers, MYSQLI_ASSOC);

if (isset($_POST['update_product'])) {
    $name = cleanInput($_POST['name']);
    $description = cleanInput($_POST['description']);
    $strength = cleanInput($_POST['strength']);
    $aroma = cleanInput($_POST['aroma']);
    $price = cleanInput($_POST['price']);
    $discount = cleanInput($_POST['discount']);
    $discount_sql = ($discount === '' || !is_numeric($discount)) ? "NULL" : $discount;
    $supplier_id = cleanInput($_POST['supplier_id']);

     // Give error-messages for missing input
    if(empty($name)){
        $error = true;
        $nameError = "Please provide a Name for the Product.";
    }
    if(empty($description)){
        $error = true;
        $descriptionnError = "Please describe the Product";
    }
    if(empty($strength)){
        $error = true;
        $strengthError = "Please choose the strength of the Product.";
    }
    if(empty($aroma)){
        $error = true;
        $aromaError = "Please choose the primary aroma of the Product.";
    }
    if(empty($price)){
        $error = true;
        $strengthError = "Please enter the current price of the Product.";
    }elseif(!preg_match("/^[0-9\.]+$/", $price)){
        $error = true;
        $ageError = "Please use only numbers and .!";
    }
    if(empty($supplier_id)){
        $error = true;
        $supplierError = "Please choose the Supplier of the Product.";
    }

        // File-Upload
    if (!empty($_FILES['picture']['name']) && $_FILES['picture']['error'] !== 4) {
        list($picture, $pictureMessage) = fileUpload($_FILES['picture'], "product");

        if (!empty($row['product_picture']) && $row['product_picture'] != 'product.jpg') {
            $oldFile = "../pictures/products/{$row['product_picture']}";
            if (is_file($oldFile)) {
                    unlink($oldFile);
            }
        }
        if (!$picture) {
            $picture = "product.jpg";
        }
    } else {
        $picture = $row['product_picture'];
        $pictureMessage = "No picture uploaded, old image kept.";
    }

    $updateSql = "UPDATE `products` SET `name`='$name',`description`='$description', `strength`='$strength', `aroma`='$aroma', `price`=$price, `discount`=$discount_sql,  `supplier_id`=$supplier_id, `product_picture`='$picture' WHERE `product_id`=$product_id";

    $result1 = mysqli_query($connect, $updateSql);
    if($result1){
        $success_error_message = "Congratulations - Product updated successfully!";
    } else{
        $success_error_message = "OOPs! Something Wrong";
    }
    header("refresh: 3; url=../dashboard.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= $links ?>
    <title>Update</title>
</head>

<body>
    <?php
    include __DIR__ . '/../components/navbar.php';
    echo $navbar;
    ?>
    <div class="container mb-3">
        <div class="row">
            <div class="col col-md-6">
                <h3 class="text py-2">Update a product</h3>
                <div class="text fs-5 text-success"><?= $success_error_message?? ""; ?>
            </div>
        </div>
            <form class="row g-3" method="POST" enctype="multipart/form-data">
                <div class="col col-md-6 mx-auto">
                    <!-- NAME -->
                    <div class="mb-3">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= $row['name'] ?>">
                        <p class="text-danger"><?= $nameError ?? "" ?></p>
                    </div>
                    <!-- STRENGTH -->
                    <div class="mb-3">
                        <label for="strength">Strength</label>
                        <select class="form-control" name="strength" id="strength">
                            <option value="balanced" <?= (($strength ?? $row['strength']) == 'balanced') ? 'selected' : '' ?>>balanced</option>
                             <option value="mild" <?= (($strength ?? $row['strength']) == 'mild') ? 'selected' : '' ?>>mild</option>
                             <option value="bold" <?= (($strength ?? $row['strength']) == 'bold') ? 'selected' : '' ?>>bold</option>
                        </select>
                        <p class="text-danger"><?= $strengthError ?? "" ?></p>
                    </div>
                    <!-- AROMA -->
                    <div class="mb-3">
                        <label for="aroma">Aroma</label>
                        <select class="form-control" name="aroma" id="aroma">
                            <option value="spicy" <?= (($aroma ?? $row['aroma']) == 'spicy') ? 'selected' : '' ?>>spicy</option>
                            <option value="chocolaty" <?= (($aroma ?? $row['aroma']) == 'chocolaty') ? 'selected' : '' ?>>chocolaty</option>
                            <option value="nutty" <?= (($aroma ?? $row['aroma']) == 'nutty') ? 'selected' : '' ?>>nutty</option>
                            <option value="fruity" <?= (($aroma ?? $row['aroma']) == 'fruity') ? 'selected' : '' ?>>fruity</option>
                            <option value="dark" <?= (($aroma ?? $row['aroma']) == 'dark') ? 'selected' : '' ?>>dark</option>
                            <option value="light" <?= (($aroma ?? $row['aroma']) == 'light') ? 'selected' : '' ?>>light</option>
                        </select>
                        <p class="text-danger"><?= $aromaError ?? "" ?></p>
                    </div>
                    <!-- PRICE -->
                    <div class="mb-3">
                        <label for="price">Price</label>
                        <input type="number" class="form-control" id="price" name="price" value="<?= $price ?? "" ?>">
                    </div>
                    <div class="mb-3">
                        <label for="discount">Discount</label>
                        <input type="number" class="form-control" id="discount" name="discount" value="<?= $row['discount'] ?>">
                        <p class="text-danger"><?= $breedError ?? "" ?></p>
                    </div>
                </div>
                <!-- BREAK -->
                <div class="col col-md-6 mx-auto">
                    <!-- SUPPLIER -->
                    <div class="mb-3">
                        <label for="supplier_id">Supplier</label>
                        <select class="form-control" name="supplier_id" id="supplier_id">
                            <?php foreach ($suppliers as $sup): ?>
                                <option value="<?= $sup['supplier_id'] ?>"
                                    <?= ($supplier_id == $sup['supplier_id']) ? "selected" : "" ?>>
                                    <?= htmlspecialchars($sup['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <p class="text-danger"><?= $supplierError ?? "" ?></p>
                    </div>
                    <!-- DESCRIPTION -->
                    <div class="mb-3">
                        <label for="description">Description</label>
                        <textarea rows=6 class="form-control" id="description" name="description"><?= $row['description'] ?></textarea>
                    <p class="text-danger"><?= $descriptionError ?? "" ?></p>
                    </div>
                    <!-- Picture Upload -->
                    <div class="mb-3">
                        <label for="picture">Picture</label>
                            <?php if (!empty($row['product_picture'])): ?>
                            <img src="../pictures/products/<?= $row['product_picture'] ?>"
                            class="img-thumbnail mb-2" width="150">
                            <?php endif; ?>
                        <input type="file" class="form-control" id="picture" name="picture">
                    </div>
                </div>

                <input type="submit" class="btn btn-success" name="update_product" value="Submit">
                <a href="../dashboard.php" class="btn btn-secondary my-3">Back</a>
            </form>
        </div>
    </div>

    <?php
    include __DIR__ . '/../components/footer.php';
    echo $footer;
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>
