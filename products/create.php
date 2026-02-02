<?php
require_once "../components/restriction_admin.php";
require_once "../components/db_connect.php";
require_once "../components/file_upload.php";
require_once "../components/navbar.php";
require_once "../components/footer.php";
require_once "../components/cleaninputs.php";

// I made a new link-connect for sub-folders, so all css and js should be importet with <?= $link ? >
require_once "../components/subfolder_links.php";
// echo "<pre>";
// print_r($_POST);
// echo "</pre>";

$sqlSuppliers = "SELECT supplier_id, name FROM supplier ORDER BY name ASC";
$resultSuppliers = mysqli_query($connect, $sqlSuppliers);
$suppliers = mysqli_fetch_all($resultSuppliers, MYSQLI_ASSOC);

if (isset($_POST['create_product'])) {

    $error=false;

    $name = cleanInput($_POST['name']);
    $description = cleanInput($_POST['description']);
    $strength = cleanInput($_POST['strength']);
    $aroma = cleanInput($_POST['aroma']);
    $price = cleanInput($_POST['price']);
    $discount = cleanInput($_POST['discount']) ?? '';
    $supplier_id = intval($_POST['supplier_id']);

    if ($discount === '') {
    $discount_sql = "NULL";
    } elseif (!is_numeric($discount)){
        $error = true;
        $discountError = "Please only use numbers.";
    } else {
        $discount_sql = $discount;
    }

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
        $priceError = "Please enter the current price of the Product.";
    }elseif(!preg_match("/^[0-9\.]+$/", $price)){
        $error = true;
        $priceError = "Please use only numbers and .!";
    }
    if(empty($supplier_id)){
        $error = true;
        $supplierError = "Please choose the Supplier of the Product.";
    }

    // File-Upload
    if (!empty($_FILES['picture']['name'])){
        list($product_picture, $pictureMessage) = fileUpload($_FILES['picture'], "product");
        if (!$product_picture) { // if upload failed
            $product_picture = "product.jpg";
        }
    } else {
        $product_picture = "product.jpg";
        $pictureMessage = "No picture uploaded, default used.";
    }


    // echo "<pre>";
    // print_r($picture);
    // echo "</pre>";
    // exit;
    if (!$error){
        $sql = "INSERT INTO `products` (`name`, `description`, `strength`, `aroma`, `price`, `discount`, `supplier_id`, `product_picture` ) VALUES ('$name', '$description', '$strength', '$aroma', $price, $discount_sql, $supplier_id, '$product_picture')";
        $result = mysqli_query($connect, $sql);
        if($result){
            $success_error_message = "Congratulations - the product was successfull created!";
        } else{
             $success_error_message = "OOPs! Something Wrong. ($pictureMessage)";
        }
        header("refresh: 3; url=../dashboard.php");
    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= $links ?>
    <title>Create</title>
</head>

<body>
    <?php
    include __DIR__ . '/../components/navbar.php';
    echo $navbar;
    ?>
    <div class="container my-5">
        <div class="row">
            <div class="col col-md-6">
                <h3 class="text py-2">Create a product</h3>
                <div class="text fs-5 text-success"><?= $success_error_message?? ""; ?></div>
            </div>
            <form  class="row g-3" method="POST" enctype="multipart/form-data">
                <div class="col col-md-6 mx-auto">
                    <!-- NAME -->
                    <div class="mb-3">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= $name ?? '' ?>">
                        <p class="text-danger"><?= $nameError ?? "" ?></p>
                    </div>
                    <!-- STRENGTH -->
                    <div class="mb-3">
                        <label for="strength">Strength</label>
                        <select class="form-control" name="strength" id="strength">
                            <option value="">-- Please select strength--</option>
                            <option value="balanced" <?php if(isset($_POST['strength']) && $_POST['strength'] === 'balanced') echo 'selected'; ?>>balanced</option>
                             <option value="mild" <?php if(isset($_POST['strength']) && $_POST['strength'] === 'mild') echo 'selected'; ?>>mild</option>
                             <option value="bold" <?php if(isset($_POST['strength']) && $_POST['strength'] === 'bold') echo 'selected'; ?>>bold</option>
                        </select>
                        <p class="text-danger"><?= $strengthError ?? "" ?></p>
                    </div>
                    <!-- AROMA -->
                    <div class="mb-3">
                        <label for="aroma">Aroma</label>
                        <select class="form-control" name="aroma" id="aroma">
                            <option value="">-- Please select aroma --</option>
                            <option value="spicy" <?php if(isset($_POST['aroma']) && $_POST['aroma'] === 'spicy') echo 'selected'; ?>>spicy</option>
                            <option value="chocolaty" <?php if(isset($_POST['aroma']) && $_POST['aroma'] === 'chocolaty') echo 'selected'; ?>>chocolaty</option>
                            <option value="nutty" <?php if(isset($_POST['aroma']) && $_POST['aroma'] === 'nutty') echo 'selected'; ?>>nutty</option>
                            <option value="fruity" <?php if(isset($_POST['aroma']) && $_POST['aroma'] === 'fruity') echo 'selected'; ?>>fruity</option>
                            <option value="dark" <?php if(isset($_POST['aroma']) && $_POST['aroma'] === 'dark') echo 'selected'; ?>>dark</option>
                            <option value="light" <?php if(isset($_POST['aroma']) && $_POST['aroma'] === 'light') echo 'selected'; ?>>light</option>
                        </select>
                        <p class="text-danger"><?= $aromaError ?? "" ?></p>
                    </div>
                    <!-- PRICE -->
                    <div class="mb-3">
                        <label for="price">Price</label>
                        <input type="text" class="form-control" id="price" name="price" value="<?= $price ?? '' ?>">
                        <p class="text-danger"><?= $priceError ?? "" ?></p>
                    </div>
                    <!-- DISCOUNT - optional -->
                    <div class="mb-3">
                        <label for="discount">Discount</label>
                        <input type="number" class="form-control" id="discount" name="discount" value="<?= $discount ?? '' ?>">
                        <p class="text-danger"><?= $discountError ?? "" ?></p>
                    </div>
                </div>
                <!-- SUPPLIER -->
                <div class="col col-md-6 mx-auto">
                    <div class="mb-3">
                        <label for="supplier_id">Supplier</label>
                        <select class="form-control" name="supplier_id" id="supplier_id">
                            <option value="">-- Please select --</option>
                            <?php foreach ($suppliers as $supplier): ?>
                                <option value="<?= $supplier['supplier_id'] ?>">
                                    <?= htmlspecialchars($supplier['name']) ?>
                                </option>
                            <?php endforeach; ?>
                            <p class="text-danger"><?= $supplierError ?? "" ?></p>
                        </select>
                    </div>
                    <!-- DESCRIPTION -->
                    <div class="mb-3">
                        <label for="description">Description</label>
                        <textarea rows=6 cols="71" id="description" name="description"><?= $description ?? '' ?></textarea>
                    <p class="text-danger"><?= $descriptionError ?? "" ?></p>
                    </div>
                    <!-- PICTURE -->
                    <div class="mb-3">
                        <label for="picture">Picture</label>
                        <input type="file" class="form-control" id="picture" name="picture">
                    </div>
                </div>
                <input type="submit" class="btn btn-success" name="create_product" value="Submit">
                <a href="../dashboard.php" class="btn btn-secondary">Back</a>
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
