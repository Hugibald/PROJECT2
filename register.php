<?php
require_once "components/restriction_logout.php";
require_once "components/db_connect.php";
require_once "components/navbar.php";
require_once "components/footer.php";
require_once "components/links.php";
require_once "components/cleaninputs.php";
require_once "components/file_upload.php";

// setting variables to false/empty
$error = false;
$fname = $lname = $email = $address = $city = $zip = $country = $password = $picture = "";
$fnameError = $lnameError = $emailError = $addressError = $passError = $cityError = $zipError = $countryError = "";

if (isset($_POST["sign-up"])) {
    $fname = cleanInput($_POST["fname"]);
    $lname = cleanInput($_POST["lname"]);
    $email = cleanInput($_POST["email"]);
    $password = ($_POST["password"]);
    // $address = cleanInput($_POST["address"]);
    // $zip = cleanInput($_POST["zip"]);
    // $city = cleanInput($_POST["city"]);
    // $country = cleanInput($_POST["country"]);

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

        if (mysqli_num_rows($result) > 0) {
            $error = true;
            $emailError = "This email has been taken. please choose another one or login";
        }
    }

    //password validation
    if (empty($password)) {
        $error = true;
        $passwordError = "The password is mandatory";
    } elseif (strlen($password) < 6 || strlen($password) > 50) {
        $error = true;
        $passwordError = "The password should be between 6 and 50";
    } elseif (preg_match('/[&<>"\']/', $password)) {
        $error = true;
        $passwordError = "Password must not contain & < > or quotation marks";
    }

    // //address validation
    // if (empty($address)) {
    //     $error = true;
    //     $addressError = "The address is mandatory";
    // } elseif (strlen($address) < 8) {
    //     $error = true;
    //     $addressError = "The address should be more than 8 characters.";
    // }

    // // ZIP validation
    // if (empty($zip)) {
    //     $error = true;
    //     $zipError = "The ZIP code is mandatory";
    // } elseif (!ctype_digit($zip)) {
    //     $error = true;
    //     $zipError = "The ZIP code must contain numbers only.";
    // } elseif (strlen($zip) < 4 || strlen($zip) > 6) {
    //     $error = true;
    //     $zipError = "The ZIP code must be between 4 and 6 digits.";
    // }


    // //city validation
    // if (empty($city)) {
    //     $error = true;
    //     $cityError = "The city is mandatory";
    // } elseif (strlen($city) < 2) {
    //     $error = true;
    //     $cityError = "The city should be more than 2 characters.";
    // }

    // //country validation
    // if (empty($country)) {
    //     $error = true;
    //     $countryError = "The country is mandatory";
    // } elseif (strlen($country) < 2) {
    //     $error = true;
    //     $countryError = "The country should be more than 2 characters.";
    // }

    // data transmission to database, if there are no errors
    if (!$error) {
        $picture = fileUpload($_FILES["picture"]);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO `users`(`first_name`, `last_name`, `email`, `user_password`, `user_picture`) VALUES ('$fname','$lname', '$email', '$hashed_password', '$picture[0]')";
        $result = mysqli_query($connect, $sql);
        if ($result) {
            echo "<div class='alert alert-success'>
                    <p>New account has been created. Redirecting...</p>
                 </div>";

            echo "<script>
                    setTimeout(function() {
                        window.location.href = 'login.php';
                    }, 2000);
                </script>";
        } else {
            echo "<div class='alert alert-danger'>
                <p>Something went wrong, please try again later ...</p>
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
    <?= $links ?>
    <title>Register</title>
</head>

<body>
    <?= $navbar ?>
    <div class="container">
        <h1>Register</h1>
        <form method="post" autocomplete="off" enctype="multipart/form-data">
            <div class="mb-3 mt-3">
                <label for="fname" class="form-label">First name</label>
                <input type="text" class="form-control" id="fname" name="fname" placeholder="First name" value="<?= $fname ?>">
                <span class="text-danger"><?= $fnameError ?? '' ?></span>
            </div>
            <div class="mb-3">
                <label for="lname" class="form-label">Last name</label>
                <input type="text" class="form-control" id="lname" name="lname" placeholder="Last name" value="<?= $lname ?>">
                <span class="text-danger"><?= $lnameError ?? '' ?></span>
            </div>
            <!-- <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" placeholder="Street Name" value="<?= $address ?>">
                <span class="text-danger"><?= $addressError ?? '' ?></span>
            </div>
            <div class="mb-3">
                <label for="zip" class="form-label">ZIP Code</label>
                <input type="text" class="form-control" id="zip" name="zip" placeholder="ZIP Code" value="<?= $zip ?>">
                <span class="text-danger"><?= $zipError ?? '' ?></span>
            </div>

            <div class="mb-3">
                <label for="city" class="form-label">City</label>
                <input type="text" class="form-control" id="city" name="city" placeholder="City" value="<?= $city ?>">
                <span class="text-danger"><?= $cityError ?? '' ?></span>
            </div>

            <div class="mb-3">
                <label for="country" class="form-label">Country</label>
                <input type="text" class="form-control" id="country" name="country" placeholder="Country" value="<?= $country ?>">
                <span class="text-danger"><?= $countryError ?? '' ?></span>
            </div> ! -->

            <div class="mb-3">
                <label for="picture" class="form-label">Profile picture</label>
                <input type="file" class="form-control" id="picture" name="picture">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">E-Mail address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="E-Mail address" value="<?= $email ?>">
                <span class="text-danger"><?= $emailError ?? '' ?></span>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password">
                <span class="text-danger"><?= $passwordError ?? '' ?></span>
            </div>

            <button name="sign-up" type="submit" class="btn btn-primary mb-3">Create account</button>

            <span class="my-4">Do you have an account already? <a href="login.php">Sign in here</a></span>
        </form>
    </div>

    <?= $footer ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>