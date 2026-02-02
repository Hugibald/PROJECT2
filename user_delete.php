<?php

require_once "components/restriction_user.php";
require_once "components/db_connect.php";

$id = $_SESSION["user"];

$sql = "SELECT * FROM users WHERE user_id = $id";
$result = mysqli_query($connect, $sql);
$row = mysqli_fetch_assoc($result);  // fetching the data
if ($row["picture"] != "avatar.png") {
    unlink("pictures/user/$row[picture]");
}

$delete = "DELETE FROM users WHERE user_id = $id";

if (mysqli_query($connect, $delete)) {
    header("Location: logout.php?logout=1");
    exit;
} else {
    echo "Error";
}

mysqli_close($connect);

?>
