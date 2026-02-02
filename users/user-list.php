<?php
require_once "../components/restriction_admin.php";
require_once "../components/db_connect.php";
require_once "../components/navbar.php";
require_once "../components/footer.php";
require_once "../components/subfolder_links.php";

if (!isset($_SESSION["admin"]) && !isset($_SESSION["user"])) {
    header("Location: login.php?restricted=true"); //send the query string to the login page to show the error message to the users who are restrivted to access this page
    exit;
}

$sqlUser = "SELECT * FROM users";
$resultUser = mysqli_query($connect, $sqlUser); // Go button

$layout = "";
// If an input is changed, update db:
if (isset($_POST['field'], $_POST['value'], $_POST['user_id'])) {
    $field = $_POST['field'];
    $value = $_POST['value'];
    $userId = (int) $_POST['user_id'];
    // To make sure only available fields can be altered
    $allowedFields = ['role', 'user_status'];
    if (!in_array($field, $allowedFields)) {
        echo 'failed: invalid field';
        exit;
    }
    // Define allowed input
    $allowedRoles = ['user', 'admin'];
    $allowedStatuses = ['free', 'warned', 'banned', 'blocked'];
    // check for cheaters
    if ($field === 'role' && !in_array($value, $allowedRoles)) {
        echo 'failed: invalid value for role';
        exit;
    }
    if ($field === 'user_status' && !in_array($value, $allowedStatuses)) {
        echo 'failed: invalid value for user_status';
        exit;
    }

    // Prepare & execute send to db
    $stmt = mysqli_prepare($connect, "UPDATE users SET {$field} = ? WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "si", $value, $userId);

    if (mysqli_stmt_execute($stmt)) {
        echo 'success';
    } else {
        echo 'failed: ' . mysqli_stmt_error($stmt);
    }
    exit;
}


if (mysqli_num_rows($resultUser) > 0) {
    $rowsUser = mysqli_fetch_all($resultUser, MYSQLI_ASSOC);
    // echo "<pre>";
    // print_r($rows);
    // echo "</pre>";
    $allStatuses = ['free', 'warned', 'blocked', 'banned'];
    $allRoles = ['user', 'admin'];


    foreach ($rowsUser as $rowU) {
        // Status-Menu to change status of user (also in db)
        $optionsStatus = '';
        foreach ($allStatuses as $status) {
            $selected = ($status === $rowU['user_status']) ? 'selected' : '';
            $optionsStatus .= "<option value='{$status}' {$selected}>"
                . ucfirst($status) .
                "</option>";
        }
        // Role-Menu to change role of user (also in db)
        $optionsRole = '';
        foreach ($allRoles as $role) {
            $selected = ($role === $rowU['role']) ? 'selected' : '';
            $optionsRole .= "<option value='{$role}' {$selected}>"
                . ucfirst($role) .
                "</option>";
        }

        $layout .= "
        <tbody>
            <tr>
                <td>{$rowU["user_id"]}</td>
                <td>{$rowU["first_name"]}</td>
                <td>{$rowU["last_name"]}</td>
                <td>{$rowU["email"]}</td>
                <td><img src='../pictures/user/{$rowU["user_picture"]}' alt='{$rowU["first_name"]}' style='width: 50px; height: auto;'></td>
                <td>
                    <select class='form-select form-select-sm'
                    onchange='updateUserField(this, {$rowU["user_id"]}, \"role\")'>
                        {$optionsRole}
                    </select>
                </td>
                <td>
                    <select class='form-select form-select-sm'
                    onchange='updateUserField(this, {$rowU["user_id"]}, \"user_status\")'>
                        {$optionsStatus}
                    </select>
                </td>
                <td>
                    <a href='update.php?id={$rowU["user_id"]}' class='btn btn-sm btn-success'>Update</a>
                    <a href='delete.php?id={$rowU['user_id']}' class='btn btn-sm btn-danger'
                       onclick=\"return confirm('Delete user?');\">
                        Delete
                    </a>
                </td>
            </tr>
    </tbody>
    ";
    }
} else {
    $layout = "<h3>No Data found</h3>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= $links ?>
    <!-- Stylesheet for status-color-code -->
    <link rel="stylesheet" href="user.css">


    <title>User management</title>
</head>

<body>
    <?= $navbar ?>
    <div class="container">
        <h1>User Management</h1>
        <table class="table table-striped my-4">
            <thead>
                <tr>
                    <th class="px-1">ID</th>
                    <th class="px-1">First name</th>
                    <th class="px-1">Last_name</th>
                    <th class="px-1">Email</th>
                    <th class="px-1">Picture</th>
                    <th class="px-1">Role</th>
                    <th class="px-1">Status</th>
                </tr>
            </thead>
            <?= $layout ?>
        </table>
        <a href='create.php' class='btn btn-sm btn-success my-2'>Create user</a>
        <a href="../dashboard.php" class="btn btn-sm btn-secondary my-2">Back</a>

    </div>
    <?= $footer ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="user-list.js"></script>
</body>

</html>