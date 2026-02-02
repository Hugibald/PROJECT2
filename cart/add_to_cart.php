<?php
session_start();


if (!isset($_SESSION["user"])) {
    header("Location: ../login.php?restricted=true");
    exit;
}

require_once "../components/db_connect.php";
require_once '../components/functions.php';

$product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
$quantity   = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT) ?? 1;

if (!$product_id || $quantity < 1) {
    header("Location: ../products.php?error=invalid_input");
    exit;
}

$user_id = $_SESSION['user'];
// ================================
// Does the product exist?
// ================================
$sql = "SELECT product_id FROM products WHERE product_id = ?";
$stmt = $connect->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    $stmt->close();
    die("Produkt existiert nicht.");
}
$stmt->close();
// ================================
// Is product in cart already?
// ================================
$sql = "SELECT quantity FROM cart_items WHERE user_id = ? AND product_id = ?";
$stmt = $connect->prepare($sql);
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->close();
    $sql = "UPDATE cart_items 
            SET quantity = quantity + ? 
            WHERE user_id = ? AND product_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("iii", $quantity, $user_id, $product_id);
    $stmt->execute();
} else {
    $stmt->close();
    $sql = "INSERT INTO cart_items (user_id, product_id, quantity)
            VALUES (?, ?, ?)";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("iii", $user_id, $product_id, $quantity);
    $stmt->execute();
}

$stmt->close();
header("Location: cart.php");
exit;
