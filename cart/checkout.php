<?php
session_start();
require_once "../components/db_connect.php";
require_once "../components/send_mail.php";
require_once "../components/send_mail_admin.php";

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php?restricted=true");
    exit;
}

$user_id = $_SESSION['user'];

// ================================
// Loading Cart Items
// ================================
$sql = "
SELECT p.product_id, p.name, p.price, p.discount, p.supplier_id, c.quantity
FROM cart_items c
JOIN products p ON c.product_id = p.product_id
WHERE c.user_id = ?
";
$stmt = $connect->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$items = [];
$total = 0;

while ($row = $result->fetch_assoc()) {
    $finalPrice = $row['price'];
    if ($row['discount'] > 0) {
        $finalPrice -= ($row['price'] * $row['discount'] / 100);
    }
    $row['final_price'] = $finalPrice;
    $total += $finalPrice * $row['quantity'];
    $items[] = $row;
}
$stmt->close();

if (empty($items)) {
    die("Your cart is empty.");
}


$sql = "SELECT first_name, last_name, email, address, ZIP, city, country FROM users WHERE user_id = ?";
$stmt = $connect->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (empty($user['address']) || empty($user['ZIP']) || empty($user['city']) || empty($user['country'])) {
    die("Please provide a shipping address before checkout.");
}


$connect->begin_transaction();

try {
    $stmt = $connect->prepare("INSERT INTO orders (order_date, order_status, total_cost, user_id) VALUES (NOW(), 'paid', ?, ?)");
    $stmt->bind_param("di", $total, $user_id);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

    // ================================
    // Safe Order Items 
    // ================================
    $stmt = $connect->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, discount, supplier_id) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($items as $item) {
        $stmt->bind_param(
            "iiidii",
            $order_id,
            $item['product_id'],
            $item['quantity'],
            $item['final_price'],
            $item['discount'],
            $item['supplier_id']
        );
        $stmt->execute();
    }
    $stmt->close();

    // ================================
    // Cart empty
    // ================================
    $stmt = $connect->prepare("DELETE FROM cart_items WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();


    $connect->commit();

    // ================================
    // Sending Mails
    // ================================
    send_mail($user, $items, $order_id, $total);       // an Kunde
    send_mail_admin($user, $items, $order_id, $total); // an Admin

    // ================================
    // Redirect to success.php
    // ================================
    header("Location: success.php?order_id=" . $order_id);
    exit;
} catch (Exception $e) {
    $connect->rollback();
    die("Error during checkout: " . $e->getMessage());
}
