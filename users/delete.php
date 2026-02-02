<?php
require_once "../components/restriction_admin.php";
require_once "../components/db_connect.php";

$id = $_GET['id'];
$sql = "SELECT * FROM `users` WHERE user_id = $id";
$result = mysqli_query($connect, $sql);
$row = mysqli_fetch_assoc($result);

// echo "<pre>";
// print_r($row);
// echo "</pre>";

if ($row['user_picture'] !== 'avatar.png') {
    $file = "../pictures/user/" . $row['user_picture'];
    if (is_file($file)) {
        unlink($file);
    }
}


$deleteSql = "DELETE FROM `users` WHERE user_id = $id";
$result = mysqli_query($connect, $deleteSql);

    if($result){
        $success_error_message = "The user was deleted.";
    } else{
        $success_error_message = "OOPs! Something Wrong";
    }
    header("refresh: 3; url=user-list.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
    <link rel="stylesheet" href="../style.css">
    <title>Delete user</title>
</head>

<body>
    <?php
    include __DIR__ . '/../components/navbar.php';
    echo $navbar;
    ?>
    <div class="container  mb-3">
        <div class="row">
            <div class="col col-md-6">
                <h3 class="text py-2">Delete a user</h3>
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
