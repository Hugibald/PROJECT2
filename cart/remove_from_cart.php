<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: ../login.php?restricted=true");
    exit;
}
require_once "../components/db_connect.php";

$user_id    = $_SESSION['user'];
$product_id = (int)$_GET['id'];

$sql = "DELETE FROM cart_items 
        WHERE user_id = ? AND product_id = ?";
$stmt = $connect->prepare($sql);
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$stmt->close();

header("Location: cart.php");
exit;
