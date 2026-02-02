<?php
require_once "components/restriction_admin.php";
require_once "components/db_connect.php";
require_once "components/cleaninputs.php";
require_once "components/hero_upload.php";
require_once "components/navbar.php";
require_once "components/footer.php";
require_once "components/links.php";

// Call JSONs
$heroFile = 'JSON/hero.json';
$productsFile = 'JSON/display_products.json';

// FileUpload
$heroDir = __DIR__ . '/pictures/';
if (!is_dir($heroDir)) {
    mkdir($heroDir, 0755, true);
}

// Hero speichern
if (isset($_POST['save_hero'])) {
  $hero = [
        'title' => cleanInput($_POST['title']),
        'subtitle' => cleanInput($_POST['subtitle']),
        'small_text' => cleanInput($_POST['small_text']),
        // 'link' => cleanInput($_POST['link']),
    ];

    // Load old Picture from JSON
    $oldHero = json_decode(file_get_contents($heroFile), true);
    $oldImage = $oldHero['image'] ?? 'hero_1.jpg';

     $hero['image'] = $oldImage;

    // If a picture is set and not empty
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
      // LOAD NEW PICTURE
      list($fileName, $msg) = fileUpload($_FILES['image']);
        if ($fileName) {
            deleteOldHeroImage($oldImage);
            $hero['image'] = $fileName;
      }
  }
    // SET IN JSON
    file_put_contents($heroFile, json_encode($hero, JSON_PRETTY_PRINT));
    $message = "Hero updated!";
}


// Save Products
if (isset($_POST['save_products'])) {
    $products = ['product_ids' => $_POST['products'] ?? []];
    file_put_contents($productsFile, json_encode($products, JSON_PRETTY_PRINT));
    $message = "Products updated!";
}

// Load Data
$hero = json_decode(file_get_contents($heroFile), true);
$productsList = mysqli_query($connect, "SELECT * FROM products");
$indexProducts = json_decode(file_get_contents($productsFile), true)['product_ids'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Edit Panel</title>
    <?= $links ?>
</head>
<body>
    <?= $navbar ?>
    <div  class="container py-5">
        <h1>Edit Homepage Content</h1>
        <a href="index.php" class="btn btn-secondary my-2">Homepage</a>
        <?php if(isset($message)) echo "<div class='alert alert-success'>$message</div>"; ?>

        <hr>

        <h4>Hero Section</h4>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-2">
                <label>Title</label>
                <input type="text" name="title" class="form-control" value="<?= $hero['title'] ?>">
            </div>
            <div class="mb-2">
                <label>Subtitle</label>
                <input type="text" name="subtitle" class="form-control" value="<?= $hero['subtitle'] ?>">
            </div>
            <div class="mb-2">
                <label>Small Text</label>
                <input type="text" name="small_text" class="form-control" value="<?= $hero['small_text'] ?>">
            </div>
            <!-- <div class="mb-2">
                <label>Link</label>
                <input type="text" name="link" class="form-control" value="<?= $hero['link'] ?>">
            </div> -->
            <div class="mb-2">
                <label>Hero Image</label>
                <input type="file" name="image" accept=".jpg,.jpeg,.png">
                <p>Current: <?= $hero['image'] ?></p>
            </div>
            <button type="submit" name="save_hero" class="btn btn-success">Save Hero</button>
        </form>

        <hr>

        <h4>Displayed Products</h4>
        <form method="post">
            <div class="mb-2">
            <?php while($row = mysqli_fetch_assoc($productsList)): ?>
                <div class="form-check">
                    <input type="checkbox" name="products[]" class="form-check-input" value="<?= $row['product_id'] ?>"
                        <?= in_array($row['product_id'], $indexProducts) ? 'checked' : '' ?>>
                    <label class="form-check-label"><?= $row['name'] ?></label>
                </div>
            <?php endwhile; ?>
            </div>
            <button type="submit" name="save_products" class="btn btn-success">Save Products</button>
        </form>
    </div>
    <?= $footer ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
