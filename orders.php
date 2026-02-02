<?php
require_once 'components/restriction_user.php';
require_once 'components/db_connect.php';
require_once 'components/navbar.php';
require_once 'components/footer.php';
require_once 'components/links.php';

$user_id = $_SESSION['user'];

//Load completed orders
//We see the status paid, shipped and delivered

$sql = "
SELECT
    order_id,
    order_date,
    total_cost,
    order_status
FROM orders
WHERE user_id = ?
  AND order_status IN ('paid', 'delivered', 'shipped')
ORDER BY order_date DESC
";

$stmt = $connect->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Orders</title>
    <?= $links ?>
</head>

<body>
    <?= $navbar ?>

    <div class="container my-5">
        <h1 class="mb-4">My Orders</h1>

        <?php if (empty($orders)): ?>
            <p>You have no completed orders yet.</p>
        <?php else: ?>

            <?php foreach ($orders as $order): ?>
                <div class="card mb-4 shadow-sm">

                    <!-- HEADER -->
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Order #<?= $order['order_id'] ?></strong><br>
                            <small><?= date('d.m.Y', strtotime($order['order_date'])) ?></small><br>
                            <?php
                            $badgeClass = match ($order['order_status']) {
                                'paid'      => 'bg-primary',
                                'shipped'   => 'bg-warning text-dark',
                                'delivered' => 'bg-success',
                                default     => 'bg-secondary'
                            };
                            ?>

                            <span class="badge <?= $badgeClass ?>">
                                <?= ucfirst($order['order_status']) ?>
                            </span>

                        </div>

                        <div class="text-end">
                            <div class="fw-bold mb-2">
                                <?= number_format($order['total_cost'], 2) ?> €
                            </div>
                            <button class="btn btn-sm btn-outline-primary"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#order<?= $order['order_id'] ?>">
                                Show details
                            </button>
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
                                    p.product_id,
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
                                        <th>Review</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $item):
                                        $final_price = $item['price'];
                                        if ($item['discount'] > 0) {
                                            $final_price -= ($item['price'] * $item['discount'] / 100);
                                        }
                                        $subtotal = $final_price * $item['quantity'];
                                    ?>
                                        <tr>
                                            <td>
                                                <img src="../pictures/products/<?= htmlspecialchars($item['product_picture']) ?>"
                                                    width="50" class="me-2">
                                                <?= htmlspecialchars($item['name']) ?>
                                            </td>
                                            <td><?= number_format($item['price'], 2) ?> €</td>
                                            <td><?= $item['quantity'] ?></td>
                                            <td><?= $item['discount'] ?> %</td>
                                            <td><?= number_format($subtotal, 2) ?> €</td>
                                            <td><a href="details.php?id=<?= $item['product_id'] ?>#rating" class="btn btn-sm btn-primary">Write Review</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php endif; ?>
    </div>

    <?= $footer ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
