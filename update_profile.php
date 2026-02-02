<?php
require_once "components/restriction_user.php";
require_once "components/db_connect.php";
require_once "components/navbar.php";
require_once "components/footer.php";
require_once "components/links.php";
require_once "components/cleaninputs.php";
require_once "components/file_upload.php";

// setting variables to false/empty
$error = false;
$fname = $lname = $email = $address = $city = $zip = $country = $picture = "";
$fnameError = $lnameError = $emailError = $addressError = $cityError = $zipError = $countryError = "";

//get data from the user table to put the initial and default value of the form fileds from the db fields
$id = isset($_SESSION["admin"]) ? $_SESSION["admin"] : $_SESSION["user"];
$redirectTo = isset($_SESSION["admin"]) ? "dashboard.php" : "userprofile.php";

// fetching existing data from database
$sql = "SELECT * FROM users WHERE user_id = $id";
$result = mysqli_query($connect, $sql);
$row = mysqli_fetch_assoc($result);

$fname = $row["first_name"];
$lname = $row["last_name"];
$email = $row["email"];
// $password = $row["user_password"];
$picture = $row["user_picture"];
$address = $row["address"];
$zip = $row["ZIP"];
$city = $row["city"];
$country = $row["country"];

//sending data do database after clicking the update button
if (isset($_POST["update"])) {
    $fname = cleanInput($_POST["fname"]);
    $lname = cleanInput($_POST["lname"]);
    $email = cleanInput($_POST["email"]);
    // $password = cleanInput($_POST["password"]);
    $address = cleanInput($_POST["address"]);
    $zip = cleanInput($_POST["zip"]);
    $city = cleanInput($_POST["city"]);
    $country = cleanInput($_POST["country"]);

    //first name validation
    if (empty($fname)) {
        $error = true;
        $fnameError = "The First Name is mandatory";
    } elseif (strlen($fname) < 3 || strlen($fname) > 30) {
        $error = true;
        $fnameError = "The First Name should be between 3 and 30";
    } elseif (!preg_match("/^[a-zA-ZäöüÄÖÜß\s]+$/", $fname)) {
        $error = true;
        $fnameError = "You are allowed just to use small and capital letter as well as spaces";
    }

    //last name validation
    if (empty($lname)) {
        $error = true;
        $lnameError = "The Last Name is mandatory";
    } elseif (strlen($lname) < 3 || strlen($lname) > 30) {
        $error = true;
        $lnameError = "The Last Name should be between 3 and 30";
    } elseif (!preg_match("/^[a-zA-ZäöüÄÖÜß\s]+$/", $lname)) {
        $error = true;
        $lnameError = "You are allowed just to use small and capital letter as well as space";
    }

    //email validation
    if (empty($email)) {
        $error = true;
        $emailError = "The email is mandatory";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $emailError = "Please write down the valid email format";
    } else {
        $sql = "select * from users where email = '$email'";
        $result = mysqli_query($connect, $sql);
    }

    // //password validation
    // if (empty($password)) {
    //     $error = true;
    //     $passwordError = "The password is mandatory";
    // } elseif (strlen($password) < 6 || strlen($password) > 50) {
    //     $error = true;
    //     $passwordError = "The password should be between 6 and 50";
    // }

    //address validation
    if(empty($address)){
        $error = false;
    } elseif (strlen($address) < 8) {
        $error = true;
        $addressError = "The address should be more than 8 characters.";
    }

    // ZIP validation
    if(empty($zip)){
        $error = false;
    } elseif (!ctype_digit($zip)) {
        $error = true;
        $zipError = "The ZIP code must contain numbers only.";
    } elseif (strlen($zip) < 4 || strlen($zip) > 6) {
        $error = true;
        $zipError = "The ZIP code must be between 4 and 6 digits.";
    }


    //city validation
    if(empty($city)){
        $error = false;
    } elseif (strlen($city) < 2) {
        $error = true;
        $cityError = "The city should be more than 2 characters.";
    }

    //country validation
    if(empty($country)){
        $error = false;
    } elseif (strlen($country) < 2) {
        $error = true;
        $countryError = "The country should be more than 2 characters.";
    }

    //Sending data to database
    if (!$error) {
        $pictureArray = fileUpload($_FILES["picture"]);
        $picture = $pictureArray[0];
        if ($_FILES["picture"]["error"] == 4) {
            $updatesql = "UPDATE `users` SET `first_name`='$fname',`last_name`='$lname',`email`='$email', `address`='$address', `ZIP`='$zip', `city`='$city', `country`='$country' WHERE user_id = $id";
        } else {
            if ($row["user_picture"] != "avatar.png") {
                unlink("pictures/user/$row[user_picture]");
            }
            $updatesql = "UPDATE `users` SET `first_name`='$fname',`last_name`='$lname',`email`='$email', `user_picture`='$picture', `address`='$address', `ZIP`='$zip', `city`='$city', `country`='$country' WHERE user_id = $id";
        }
        $result = mysqli_query($connect, $updatesql);
        if ($result) {
            echo "<div class='alert alert-success'>
                    <p>Your Account has been updated successfully.</p>
                 </div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile Update</title>
    <?= $links ?>
</head>

<body>
    <?= $navbar ?>
    <div class="container">
        <div class="row my-3">
            <h1>Update your profile</h1>
            <div><?= $success_error_message ?? "" ?></div>
        </div>

        <div class="row">
            <form method="post" autocomplete="off" enctype="multipart/form-data">
                <!-- FIRST NAME -->
                <div class="mb-3 mt-3">
                    <label for="fname" class="form-label">First name</label>
                    <input type="text" class="form-control" id="fname" name="fname" placeholder="First name" value="<?= $row['first_name'] ?>">
                    <span class="text-danger"><?= $fnameError ?? "" ?></span>
                </div>
                <!-- LAST NAME -->
                <div class="mb-3">
                    <label for="lname" class="form-label">Last name</label>
                    <input type="text" class="form-control" id="lname" name="lname" placeholder="Last name" value="<?= $row['last_name'] ?>">
                    <span class="text-danger"><?= $lnameError ?? '' ?></span>
                </div>
                <!-- ADDRESS -->
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" name="address" placeholder="Street Name" value="<?= $row['address'] ?>">
                    <span class="text-danger"><?= $addressError ?? '' ?></span>
                </div>
                <!-- ZIP -->
                <div class="mb-3">
                    <label for="zip" class="form-label">ZIP Code</label>
                    <input type="text" class="form-control" id="zip" name="zip" placeholder="ZIP Code" value="<?= $row['ZIP'] ?>">
                    <span class="text-danger"><?= $zipError ?? '' ?></span>
                </div>
                <!-- CITY -->
                <div class="mb-3">
                    <label for="city" class="form-label">City</label>
                    <input type="text" class="form-control" id="city" name="city" placeholder="City" value="<?= $row['city'] ?>">
                    <span class="text-danger"><?= $cityError ?? '' ?></span>
                </div>
                <!-- COUNTRY -->
                <div class="mb-3">
                    <label for="country" class="form-label">Country</label>
                    <input type="text" class="form-control" id="country" name="country" placeholder="Country" value="<?= $row['country'] ?>">
                    <span class="text-danger"><?= $countryError ?? '' ?></span>
                </div>
                <!-- PICTURE -->
                <!-- Picture Upload -->
                <div class="mb-3">
                    <label for="picture" class="form-label">Profile Picture:</label>
                    <!-- Show current picture -->
                    <div class="mb-3 row align-items-center">
                      <div class="col-md-2">
                        <img src="pictures/user/<?= $row['user_picture']; ?>" alt="Current Profile Picture" style="max-width: 100px; height: auto; border-radius: 8px;">
                      </div>
                      <!-- File upload -->
                      <div class="col-md-10">
                        <input type="file" class="form-control" name="picture" id="picture">
                      </div>
                    </div>
                </div>
                <!-- EMAIL -->
                <div class="mb-3">
                    <label for="email" class="form-label">E-Mail address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email address" value="<?= $row['email'] ?>">
                    <span class="text-danger"><?= $emailError ?? '' ?></span>
                </div>
                <!-- <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password">
                    <span class="text-danger"><?= $passwordError ?></span>
                </div> !-->
                <button type="submit" class="btn btn-primary mb-3" name="update">Update Profile</button>
                <a href='user_delete.php?id=$_SESSION["user"]' class='btn btn btn-danger mb-3'
                       onclick="return confirm('Are you sure you want to delete your account?');">
                        Delete
                    </a>
                <a href='userprofile.php' class='btn btn-secondary mb-3'>Back To Profile</a>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <?= $footer ?>
</body>


</html>
