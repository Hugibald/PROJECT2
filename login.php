<?php
require_once "components/restriction_logout.php";
require_once "components/db_connect.php";
require_once "components/navbar.php";
require_once "components/footer.php";
require_once "components/links.php";

if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $error = false;
    if (empty($email)) {
        $error = true;
        $emailError = "The E-Mail is required";
    }
    if (empty($password)) {
        $error = true;
        $passwordError = "The password is required";
    }

    if (!$error) {

        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row1 = $result->fetch_assoc();

            // Check PW
            if (!password_verify($password, $row1["user_password"])) {
                $pageMessage = "Your email or password is incorrect";
            } else {

                // Check status
                if ($row1["user_status"] === "banned") {
                    $emailError = "You are banned.";
                } elseif ($row1["user_status"] === "blocked") {
                    $emailError = "You are blocked. Try again after some days.";
                } else {

                    // Login successfull
                    if ($row1["role"] === "admin") {
                        $_SESSION["admin"] = $row1["user_id"];
                        header("Location: dashboard.php");
                        exit;
                    } else {
                        $_SESSION["user"] = $row1["user_id"];
                        header("Location: userprofile.php");
                        exit;
                    }
                }
            }
        } else {
            $pageMessage = "Your email or password is incorrect";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= $links ?>
    <title>Login</title>
</head>

<body>
    <?= $navbar ?>
    <div class="container mb-5">
        <div class="row">
            <h1>Login</h1>
            <p class="text text-danger"><?= $pageMessage ?? "" ?></p>
        </div>
        <div class="row">
            <form method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">E-Mail address</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>">
                    <p class="text text-danger"><?= $emailError ?? "" ?></p>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password">
                    <p class="text text-danger"><?= $passwordError ?? "" ?></p>
                </div>

                <button type="submit" class="btn btn-primary mb-2" name="login">Login!</button>
                <a href="register.php" class="btn btn-secondary mb-2">Register instead!</a>
            </form>
        </div>
    </div>
    <?= $footer ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>