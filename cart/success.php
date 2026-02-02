<?php
session_start();
require_once '../components/db_connect.php';
require_once '../components/navbar.php';
require_once '../components/footer.php';
require_once '../components/links.php';

// Prüfen, ob User eingeloggt ist
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php?restricted=true");
    exit;
}

$user_id = $_SESSION['user'];

// Prüfen, ob order_id über GET übergeben wurde
if (!isset($_GET['order_id'])) {
    header("Location: ../products.php");
    exit;
}

$order_id = (int)$_GET['order_id'];

// Bestellung aus der DB laden
$stmt = $connect->prepare("
    SELECT o.order_id, o.order_date, o.total_cost, oi.product_id, oi.quantity, oi.price, oi.discount, p.name, p.product_picture, s.name AS supplier_name
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    JOIN products p ON oi.product_id = p.product_id
    JOIN supplier s ON oi.supplier_id = s.supplier_id
    WHERE o.order_id = ? AND o.user_id = ?
");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Wenn keine Items gefunden, zurück zu Produkten
if (empty($order_items)) {
    header("Location: ../products.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Successful</title>
    <?= $links ?>
</head>

<body>
    <?= $navbar ?>

    <div class="container my-5">
        <h1 class="text-center mb-4">Thank you for your order!</h1>
        <p class="text-center">Your order number is: <strong><?= htmlspecialchars($order_id) ?></strong></p>
        <p class="text-center">We received your order and will process it as soon as possible.</p>

        <table class="table mt-4">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Discount</th>
                    <th>Quantity</th>
                    <th>Supplier</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order_items as $item):
                    $subtotal = $item['price'] * $item['quantity'];
                ?>
                    <tr>
                        <td>
                            <img src="../pictures/products/<?= htmlspecialchars($item['product_picture']) ?>" width="60">
                            <?= htmlspecialchars($item['name']) ?>
                        </td>
                        <td><?= number_format($item['price'], 2) ?> €</td>
                        <td><?= $item['discount'] ?> %</td>
                        <td><?= $item['quantity'] ?></td>
                        <td><?= htmlspecialchars($item['supplier_name']) ?></td>
                        <td><?= number_format($subtotal, 2) ?> €</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <h4 class="text-end">Total: <?= number_format($order_items[0]['total_cost'], 2) ?> €</h4>
        <div class="text-center mt-3">
            <a href="../products.php" class="btn btn-primary">Continue shopping</a>
        </div>
    </div>

    <?= $footer ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>
