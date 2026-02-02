<?php
require_once "../components/restriction_admin.php";
require_once "../components/db_connect.php";
// For cleaning inputs!! IMPORTANT!
require_once "../components/cleaninputs.php";
require_once "../components/file_upload.php";
require_once "../components/navbar.php";
require_once "../components/footer.php";
// Link-component for subfolder:
require_once "../components/subfolder_links.php";

// Set error
$error = false;

// On submit clean the inputs for security
if (isset($_POST['create_supplier'])) {
    $name = cleanInput($_POST['name']);
    $location = cleanInput($_POST['location']);
    $story = cleanInput($_POST['story']);

    // Give error-messages for missing input
    if(empty($name)){
        $error = true;
        $nameError = "Please provide a Name for the Supplier.";
    }
    if(empty($location)){
        $error = true;
        $locationError = ">Please choose a Location for the Supplier";
    }
    if(empty($story)){
        $error = true;
        $storyError = "Please write a story for the Supplier.";
    }

    // Only if everything is filled out,
    if(!$error){
        // Upload picture
        if (!empty($_FILES['supplier_picture']['name'])){
        list($supplier_picture, $pictureMessage) = fileUpload($_FILES['supplier_picture'], "supplier");
            if (!$supplier_picture) { // if upload failed
                $supplier_picture = "supplier.jpg";
            }
        // if no picture uploaded, set default
        } else {
            $supplier_picture = "supplier.jpg";
            $pictureMessage = "No picture uploaded, default used.";
        }
        // Send to DB
        $sql = "INSERT INTO `supplier` (`name`, `location`, `story`, `supplier_picture` ) VALUES ('$name', '$location', '$story', '$supplier_picture')";

        $result = mysqli_query($connect, $sql);
        // Send message
        if($result){
            $success_error_message = "Congratulations - the supplier was successfull created!";
            header("refresh: 3; url=dashboard.php");
        } else{
             $success_error_message = "OOPs! Something Wrong";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Links for Bootstrap CSS and style.css -->
    <?= $links ?>

    <title>Create Supplier</title>
</head>

<body>
    <!-- NAVBAR -->
    <?php
    include __DIR__ . '/../components/navbar.php';
    echo $navbar;
    ?>
    <div class="container my-5">
        <div class="row">
            <div class="col col-md-6">
                <h3 class="text py-2">Create a supplier</h3>
                <!-- Send messages: -->
                <div class="text fs-5 text-success"><?= $success_error_message?? ""; ?></div>
            </div>
            <!-- Form -->
            <form  class="row g-3" method="POST" enctype="multipart/form-data">
                <div class="col col-md-6 mx-auto">
                    <!-- NAME -->
                    <div class="mb-3">
                        <label for="name">Supplier Name</label>
                        <input type="text" class="form-control" id="name" name="name">
                        <p class="text-danger"><?= $nameError ?? "" ?></p>
                    </div>
                    <!-- LOCATION_SELECTOR -->
                    <div class="mb-3">
                        <label for="location">Location</label>
                        <select class="form-control" name="location" id="location">
                            <option value="">-- Please select --</option>
                            <option value="Africa"<?php if(isset($_POST['location']) && $_POST['location'] === 'Africa') echo 'selected'; ?>>Africa</option>
                            <option value="South America"<?php if(isset($_POST['location']) && $_POST['location'] === 'South America') echo 'selected'; ?>>South America</option>
                             <option value="Asia" <?php if(isset($_POST['location']) && $_POST['location'] === 'Asia') echo 'selected'; ?>>Asia</option>
                        </select>
                        <p class="text-danger"><?= $locationError ?? "" ?></p>
                    </div>
                    <!-- STORY -->
                    <div class="mb-3">
                        <label for="story">Supplier´s Story</label>
                        <textarea class="form-control" id="story" name="story"><?= $story ?? '' ?></textarea>
                        <p class="text-danger"><?= $storyError ?? "" ?></p>
                    </div>
                    <!-- PICTURE-UPLOAD -->
                    <div class="mb-3">
                        <label for="supplier_picture" class="form-label">Supplier´s Picture:</label>
                        <input type="file" class="form-control" name="supplier_picture" id="supplier_picture">
                        <p class="text-danger"></p>
                    </div>
                </div>
                <input type="submit" class="btn btn-success" name="create_supplier" value="Submit">
                <a href="dashboard.php" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
    <!-- Footer -->
    <?php
    include __DIR__ . '/../components/footer.php';
    echo $footer;
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>
