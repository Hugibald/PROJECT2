<?php
require_once '../components/restriction_admin.php';
require_once '../components/db_connect.php';
require_once '../components/navbar.php';
require_once '../components/footer.php';
require_once '../components/subfolder_links.php';

/* ===========================
   STATUS UPDATE
=========================== */
if (isset($_POST['update_status'])) {
    $order_id = (int)$_POST['order_id'];
    $new_status = $_POST['order_status'];

    // delivered darf NICHT mehr geändert werden
    $stmt = $connect->prepare("
        UPDATE orders
        SET order_status = ?
        WHERE order_id = ?
          AND order_status != 'delivered'
    ");
    $stmt->bind_param('si', $new_status, $order_id);
    $stmt->execute();
    $stmt->close();
}

/* ===========================
   LOAD ALL ORDERS
=========================== */
$sql = "
SELECT
    o.order_id,
    o.order_date,
    o.order_status,
    o.total_cost,
    u.first_name,
    u.last_name,
    u.email,
    u.address,
    u.ZIP,
    u.city,
    u.country
FROM orders o
JOIN users u ON o.user_id = u.user_id
ORDER BY
    (o.order_status = 'delivered') ASC,
    o.order_date DESC
";

$result = $connect->query($sql);
$orders = $result->fetch_all(MYSQLI_ASSOC);

/* ===========================
   DROPDOWN
=========================== */
if (isset($_POST['field'], $_POST['value'], $_POST['order_id'])) {

    $orderId = (int)$_POST['order_id'];
    $field = $_POST['field'];
    $value = $_POST['value'];

    // Sicherheit: nur erlaubte Felder
    if ($field !== 'order_status') {
        exit('invalid field');
    }

    $stmt = $connect->prepare("
        UPDATE orders
        SET order_status = ?
        WHERE order_id = ?
          AND order_status != 'delivered'
    ");

    $stmt->bind_param('si', $value, $orderId);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'db error';
    }

    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin – Orders</title>
    <?= $links ?>
</head>

<body>
    <?= $navbar ?>

    <div class="container">
        <h1>Order Management</h1>

        <?php if (empty($orders)): ?>
            <p>No orders found.</p>
        <?php else: ?>

            <?php foreach ($orders as $order):
                $allStatuses = ['pending', 'paid', 'shipped', 'delivered'];
                $optionsStatus = '';
                foreach ($allStatuses as $status) {
                    $selected = ($status === $order['order_status']) ? 'selected' : '';
                    $optionsStatus .= "<option value='{$status}' {$selected}>"
                        . ucfirst($status) .
                        "</option>";
                }

                $isDelivered = $order['order_status'] === 'delivered';
                $hideAddress = in_array($order['order_status'], ['shipped', 'delivered']);

                $badgeClass = match ($order['order_status']) {
                    'paid'      => 'bg-primary',
                    'shipped'   => 'bg-warning text-dark',
                    'delivered' => 'bg-success',
                    default     => 'bg-secondary'
                };
            ?>

                <div class="card mb-4 shadow-sm <?= $isDelivered ? 'opacity-50 small' : '' ?>">

                    <!-- HEADER -->
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Order #<?= $order['order_id'] ?></strong><br>

                            <small>
                                <?= htmlspecialchars($order['first_name']) ?>
                                <?= htmlspecialchars($order['last_name']) ?>
                                (<?= htmlspecialchars($order['email']) ?>)
                            </small><br>

                            <?php if (!$hideAddress): ?>
                                <small class="text-muted">
                                    <?= htmlspecialchars($order['address']) ?><br>
                                    <?= htmlspecialchars($order['ZIP']) ?>
                                    <?= htmlspecialchars($order['city']) ?><br>
                                    <?= htmlspecialchars($order['country']) ?>
                                </small><br>
                            <?php endif; ?>

                            <small><?= date('d.m.Y', strtotime($order['order_date'])) ?></small><br>

                            <select class="form-select form-select-sm"
                                    onchange="updateOrderField(this, <?= $order['order_id'] ?>, 'order_status')">
                                <?= $optionsStatus ?>
                            </select>

                            <!-- <span class="badge <?= $badgeClass ?>">
                                <?= ucfirst($order['order_status']) ?>
                            </span> -->
                        </div>

                        <div class="text-end">
                            <div class="fw-bold mb-2">
                                <?= number_format($order['total_cost'], 2) ?> €
                            </div>

                            <?php if (!$isDelivered): ?>
                                <button class="btn btn-sm btn-outline-primary"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#order<?= $order['order_id'] ?>">
                                    Show details
                                </button>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Delivered – closed</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- DETAILS -->
                    <div class="collapse" id="order<?= $order['order_id'] ?>">
                        <div class="card-body">

                            <?php
                            $sql_items = "
                            SELECT
                                oi.quantity,
                                oi.price,
                                oi.discount,
                                p.name,
                                p.product_picture
                            FROM order_items oi
                            JOIN products p ON oi.product_id = p.product_id
                            WHERE oi.order_id = ?
                        ";
                            $stmt_items = $connect->prepare($sql_items);
                            $stmt_items->bind_param('i', $order['order_id']);
                            $stmt_items->execute();
                            $items = $stmt_items->get_result()->fetch_all(MYSQLI_ASSOC);
                            $stmt_items->close();
                            ?>

                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th>Discount</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $item):
                                        $price = $item['price'];
                                        if ($item['discount']) {
                                            $price -= ($item['price'] * $item['discount'] / 100);
                                        }
                                        $subtotal = $price * $item['quantity'];
                                    ?>
                                        <tr>
                                            <td>
                                                <img src="../pictures/products/<?= htmlspecialchars($item['product_picture']) ?>"
                                                    width="40" class="me-2">
                                                <?= htmlspecialchars($item['name']) ?>
                                            </td>
                                            <td><?= number_format($price, 2) ?> €</td>
                                            <td><?= $item['quantity'] ?></td>
                                            <td><?= $item['discount'] ?? 0 ?> %</td>
                                            <td><?= number_format($subtotal, 2) ?> €</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                            <!-- STATUS UPDATE
                            <?php if (!$isDelivered): ?>
                                <form method="post" class="d-flex gap-2 align-items-center mt-3">
                                    <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">

                                    <select name="order_status" class="form-select w-auto">
                                        <option value="pending" <?= $order['order_status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="paid" <?= $order['order_status'] === 'paid' ? 'selected' : '' ?>>Paid</option>
                                        <option value="shipped" <?= $order['order_status'] === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                        <option value="delivered">Delivered</option>
                                    </select>

                                    <button type="submit" name="update_status" class="btn btn-success">
                                        Update Status
                                    </button>
                                </form>
                            <?php endif; ?> -->

                        </div>
                    </div>

                </div>

            <?php endforeach; ?>

        <?php endif; ?>
    </div>

    <?= $footer ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="orders.js"></script>
</body>

</html>
