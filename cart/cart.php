<?php
require_once '../components/db_connect.php';
require_once '../components/restriction_user.php';
require_once '../components/cleaninputs.php';
require_once '../components/navbar.php';
require_once '../components/footer.php';
require_once '../components/links.php';

$user_id = $_SESSION['user'];

// ================================
// Get user address
// ================================
$sqlU = "SELECT * FROM users WHERE user_id = ?";
$stmt = $connect->prepare($sqlU);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

$address = $user['address'] ?? '';
$zip     = $user['ZIP'] ?? '';
$city    = $user['city'] ?? '';
$country = $user['country'] ?? '';
$error = false;

// ================================
// Update address if form submitted
// ================================
if (isset($_POST["update"])) {
    $address = cleanInput($_POST["address"]);
    $zip     = cleanInput($_POST["zip"]);
    $city    = cleanInput($_POST["city"]);
    $country = cleanInput($_POST["country"]);

    // Address validation
    if (empty($address) || strlen($address) < 8) {
        $error = true;
        $addressError = "Address must be at least 8 characters.";
    }
    if (empty($zip) || !ctype_digit($zip) || strlen($zip) < 4 || strlen($zip) > 6) {
        $error = true;
        $zipError = "ZIP must be 4-6 digits.";
    }
    if (empty($city) || strlen($city) < 2) {
        $error = true;
        $cityError = "City must be at least 2 characters.";
    }
    if (empty($country) || strlen($country) < 2) {
        $error = true;
        $countryError = "Country must be at least 2 characters.";
    }

    if (!$error) {
        $updatesql = "UPDATE users SET address=?, ZIP=?, city=?, country=? WHERE user_id=?";
        $stmt = $connect->prepare($updatesql);
        $stmt->bind_param("ssssi", $address, $zip, $city, $country, $user_id);
        $stmt->execute();
        $stmt->close();

        header("Location: cart.php"); // Refresh page to show PayPal button
        exit;
    }
}

// ================================
// Load cart items
// ================================
$sql = "
SELECT p.product_id, p.name, p.price, p.discount, p.product_picture, c.quantity
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
    $price = $row['price'];
    if ($row['discount'] > 0) {
        $price -= $row['price'] * $row['discount'] / 100;
    }
    $row['final_price'] = $price;
    $row['subtotal'] = $price * $row['quantity'];
    $total += $row['subtotal'];
    $items[] = $row;
}
$paypalTotal = number_format($total, 2, '.', '');

$stmt->close();
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= $links ?>
    <title>Shopping Cart</title>
</head>

<body>
    <?= $navbar ?>

    <div class="container my-5">
        <h1>My Shopping Cart</h1>

        <?php if (empty($items)): ?>
            <p>My cart is empty.</p>
        <?php else: ?>
            <table class="table align-middle">
    <thead>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Discount</th>
            <th>Quantity</th>
            <th>Total</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($items as $item): ?>
        <tr>
            <td>
                <img src="../pictures/products/<?= htmlspecialchars($item['product_picture']) ?>" width="60">
                <?= htmlspecialchars($item['name']) ?>
            </td>

            <td><?= number_format($item['price'], 2) ?> €</td>

            <td>
                <?php if ($item['discount'] > 0): ?>
                    <span class="text-warning fw-bold">
                        -<?= (int)$item['discount'] ?> %
                    </span>
                <?php else: ?>
                    —
                <?php endif; ?>
            </td>

            <td>
                <form method="post" action="update_cart.php" class="d-flex">
                    <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1"
                           class="form-control me-2">
                    <button class="btn btn-primary btn-sm">Update</button>
                </form>
            </td>

            <td><?= number_format($item['subtotal'], 2) ?> €</td>

            <td>
                <a href="remove_from_cart.php?id=<?= $item['product_id'] ?>"
                   class="btn btn-danger btn-sm">✕</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
            <h4 class="text-end">Total: <?= number_format($total, 2) ?> €</h4>

            <!-- =======================
             Shipping address form
             ======================= -->
            <hr>
            <h4>Shipping Address</h4>
            <form method="post" autocomplete="off">
                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" value="<?= $address ?>">
                    <span class="text-danger"><?= $addressError ?? '' ?></span>
                </div>
                <div class="mb-3">
                    <label class="form-label">ZIP Code</label>
                    <input type="text" name="zip" class="form-control" value="<?= $zip ?>">
                    <span class="text-danger"><?= $zipError ?? '' ?></span>
                </div>
                <div class="mb-3">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-control" value="<?= $city ?>">
                    <span class="text-danger"><?= $cityError ?? '' ?></span>
                </div>
                <div class="mb-3">
                    <label class="form-label">Country</label>
                    <input type="text" name="country" class="form-control" value="<?= $country ?>">
                    <span class="text-danger"><?= $countryError ?? '' ?></span>
                </div>
                <button type="submit" name="update" class="btn btn-primary">Save Address</button>
            </form>

            <!-- =======================
             PayPal Buttons
             ======================= -->
            <?php if (!empty($address) && !empty($zip) && !empty($city) && !empty($country)): ?>
                <hr>
                <div id="paypal-button-container" class="d-flex justify-content-center mt-3"></div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <?= $footer ?>

    <!-- PayPal SDK -->
    <?php if (!empty($address) && !empty($zip) && !empty($city) && !empty($country)): ?>
        <script src="https://www.paypal.com/sdk/js?client-id=AXIRlzexuoadgnYKDw3q1T30qCq2RDHCCws0DjGY7xR4cc5a0iN3TC6Az9BSW8Pfr3qcm1GanNcZjEyb&currency=EUR"></script>
        <script>
const paypalTotal = '<?= $paypalTotal ?>';

paypal.Buttons({
    createOrder: function (data, actions) {
        return actions.order.create({
            purchase_units: [{
                amount: {
                    value: paypalTotal
                }
            }]
        });
    },
    onApprove: function (data, actions) {
        return actions.order.capture().then(function () {
            window.location.href = 'checkout.php';
        });
    }
}).render('#paypal-button-container');

        </script>
    <?php endif; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>