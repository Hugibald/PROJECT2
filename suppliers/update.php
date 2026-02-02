<?php
require_once "../components/restriction_admin.php";
require_once "../components/db_connect.php";
// IMPORTANT: Clean Inputs:
require_once "../components/cleaninputs.php";
require_once "../components/file_upload.php";
// Link-component for subfolder:
require_once "../components/subfolder_links.php";

//===========
//getting suppliers
//===========
$supplier_id = intval($_GET['id']);
$supplier_sql = "SELECT * FROM `supplier` WHERE supplier_id = $supplier_id";
$result = mysqli_query($connect, $supplier_sql);
$row = mysqli_fetch_assoc($result);
if (!$row) {
    die("Supplier not found");
}

$error = false;
// On Submit-Button click:
if (isset($_POST['update_supplier'])) {
    // Make sure inputs are clean
    $name = cleanInput($_POST['name']);
    $location = cleanInput($_POST['location']);
    $story = cleanInput($_POST['story']);
    // Check if empty
    if(empty($name)){
        $error = true;
        $nameError = "Please provide a Name for the Supplier.";
    }
    if(empty($location)){
        $error = true;
        $locationError = "Please select a Location for the Supplier";
    }
    if(empty($story)){
        $error = true;
        $storyError = "Please enter the Supplier´s story.";
    }
// If no error:
    if(!$error){
        // Default update
        $updateSql = "UPDATE supplier
                    SET name='$name',
                        location='$location',
                        story='$story'
                    WHERE supplier_id=$supplier_id";

        // Fileupload
        if (!empty($_FILES['picture']['name']) && $_FILES['picture']['error'] === 0) {

            list($newPicture, $pictureMessage) = fileUpload($_FILES['picture'], "supplier");

            // successfull upload?
            if ($newPicture) {

                // delete old picture
                if (!empty($row['supplier_picture']) && $row['supplier_picture'] !== 'supplier.png') {
                    $oldFile = "../pictures/supplier/" . $row['supplier_picture'];
                    if (is_file($oldFile)) {
                        unlink($oldFile);
                    }
                }

                // Update with new picture
                $updateSql = "UPDATE supplier
                            SET name='$name',
                                location='$location',
                                story='$story',
                                supplier_picture='$newPicture'
                            WHERE supplier_id=$supplier_id";
            }
        }
        $resultUpdate = mysqli_query($connect, $updateSql);
        // If success, redirect
        if ($resultUpdate) {
            header("Location: dashboard.php");
            exit;
        } else {
            echo mysqli_error($connect);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- bootstrap and css -->
    <?= $links ?>
    <title>Update Supplier</title>
</head>

<body>
    <!-- Navbar -->
    <?php
    include __DIR__ . '/../components/navbar.php';
    echo $navbar;
    ?>
    <div class="container  mb-3">
        <div class="row">
            <div class="col col-md-6">
                <h3 class="text py-2">Update Supplier</h3>
                <!-- Show messages -->
                <div class="text fs-5 text-success"><?= $success_error_message?? ""; ?></div>
            </div>
            <form class="row g-3" method="POST" enctype="multipart/form-data">
                <div class="col col-md-6 mx-auto">
                    <!-- NAME -->
                    <div class="mb-3">
                        <label for="name">Supplier´s name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($name ?? $row['name']) ?>">
                        <p class="text-danger"><?= $nameError ?? "" ?></p>
                    </div>
                    <!-- STORY -->
                    <div class="mb-3">
                        <label for="story">Supplier´s Story</label>
                        <textarea rows=6 class="form-control" id="story" name="story"><?= htmlspecialchars($story ?? $row['story']) ?></textarea>
                        <p class="text-danger"><?= $storyError ?? "" ?></p>
                    </div>
                </div>
                <div class="col col-md-6 mx-auto">
                     <!-- LOCATION -->
                    <div class="mb-3">
                        <label for="location">Location</label>
                        <select class="form-control" name="location" id="location">
                            <option value="">-- Please select Location --</option>
                            <option value="Africa" <?= (($location ?? $row['location']) == 'Africa') ? 'selected' : '' ?>>Africa</option>
                            <option value="South America" <?= (($location ?? $row['location']) == 'South America') ? 'selected' : '' ?>>South America</option>
                            <option value="Asia" <?= (($location ?? $row['location']) == 'Asia') ? 'selected' : '' ?>>Asia</option>
                        </select>
                        <p class="text-danger"><?= $locationError ?? "" ?></p>
                    </div>
                    <!-- PICTURE -->
                    <div class="mb-3">
                        <label for="picture">Picture</label>
                            <?php if (!empty($row['supplier_picture'])): ?>
                            <img src="../pictures/supplier/<?= $row['supplier_picture'] ?>"
                            class="img-thumbnail mb-2" width="150">
                            <?php endif; ?>
                        <input type="file" class="form-control" id="picture" name="picture">
                    </div>
                </div>
                <input type="submit" class="btn btn-success" name="update_supplier" value="Submit">
                <a href="../dashboard.php" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
    <!-- FOOTER -->
    <?php
    include __DIR__ . '/../components/footer.php';
    echo $footer;
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>
